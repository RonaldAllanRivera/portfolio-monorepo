<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CertificationController extends Controller
{
    /**
     * List certifications ordered by sort_order then issue_date desc.
     */
    public function index(): JsonResponse
    {
        $rows = Certification::with(['user:id,name', 'organization:id,name,website'])
            ->ordered()
            ->get();

        $skillsMap = $this->loadSkillsMap($rows->pluck('id')->all());
        $items = $rows->map(fn ($c) => $this->formatCertification($c, $skillsMap));

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Show one certification by id.
     */
    public function show(int $id): JsonResponse
    {
        $cert = Certification::with(['user:id,name', 'organization:id,name,website'])->find($id);

        if (! $cert) {
            return response()->json([
                'success' => false,
                'message' => 'Certification not found',
            ], 404);
        }

        $skillsMap = $this->loadSkillsMap([$cert->id]);
        return response()->json([
            'success' => true,
            'data' => $this->formatCertification($cert, $skillsMap),
        ]);
    }

    /**
     * Non-expired certifications (or no expiration set).
     */
    public function current(): JsonResponse
    {
        $today = now()->startOfDay();

        $rows = Certification::with(['user:id,name', 'organization:id,name,website'])
            ->where(function ($q) use ($today) {
                $q->whereNull('expiration_date')
                  ->orWhereDate('expiration_date', '>=', $today);
            })
            ->ordered()
            ->get();

        $skillsMap = $this->loadSkillsMap($rows->pluck('id')->all());
        $items = $rows->map(fn ($c) => $this->formatCertification($c, $skillsMap));

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Normalize a certification for API responses.
     */
    private function formatCertification(Certification $c, ?array $skillsMap = null): array
    {
        $isExpired = $c->expiration_date !== null && $c->expiration_date->isBefore(now()->startOfDay());
        $totalMinutes = (int) ($c->total_minutes ?? 0);
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;
        $durationLabel = $totalMinutes > 0
            ? trim(($hours ? $hours . 'h' : '') . ' ' . ($minutes ? $minutes . 'm' : ''))
            : null;

        return [
            'id' => $c->id,
            'name' => $c->name,
            'issuer' => $c->organization ? [
                'id' => $c->organization->id,
                'name' => $c->organization->name,
                'website' => $c->organization->website,
            ] : null,
            'issue_date' => $c->issue_date?->format('Y-m-d'),
            'issue_date_formatted' => $c->issue_date?->format('M d, Y'),
            'expiration_date' => $c->expiration_date?->format('Y-m-d'),
            'expiration_date_formatted' => $c->expiration_date?->format('M d, Y'),
            'is_expired' => $isExpired,
            'is_valid' => ! $isExpired,
            'credential_id' => $c->credential_id,
            'credential_url' => $c->credential_url,
            'total_minutes' => $totalMinutes ?: null,
            'duration' => [
                'hours' => $totalMinutes ? $hours : null,
                'minutes' => $totalMinutes ? $minutes : null,
                'label' => $durationLabel,
            ],
            // Return skills from the pivot relation (joined table)
            'skills' => $this->skillsFor($c->id, $skillsMap, namesOnly: true),
            'skills_full' => $this->skillsFor($c->id, $skillsMap, namesOnly: false),
            'media' => $this->formatMedia($c->media),
            'sort_order' => $c->sort_order,
            'created_at' => $c->created_at?->toIso8601String(),
            'updated_at' => $c->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Build a map of certification_id => [skills...], using explicit joins.
     */
    private function loadSkillsMap(array $certIds): array
    {
        if (empty($certIds)) return [];
        $rows = DB::table('certification_skill as cs')
            ->join('skills as s', 's.id', '=', 'cs.skill_id')
            ->whereIn('cs.certification_id', $certIds)
            ->orderBy('s.sort_order')
            ->orderBy('s.name')
            ->get(['cs.certification_id', 's.id', 's.name', 's.category']);

        $map = [];
        foreach ($rows as $r) {
            $map[$r->certification_id][] = [
                'id' => $r->id,
                'name' => $r->name,
                'category' => $r->category,
            ];
        }
        return $map;
    }

    /**
     * Helper to pull either names or full objects for a certification id.
     */
    private function skillsFor(int $certId, ?array $skillsMap, bool $namesOnly): array
    {
        $list = $skillsMap[$certId] ?? [];
        if ($namesOnly) {
            return collect($list)->pluck('name')->filter()->values()->all();
        }
        return array_values($list);
    }

    /**
     * Build public URLs for files stored on the R2 disk.
     */
    private function formatMedia(?array $media): array
    {
        if (! $media) {
            return [];
        }

        return array_map(function ($path) {
            return [
                'path' => $path,
                'url' => Storage::disk('r2')->url($path),
                'full_url' => Storage::disk('r2')->url($path),
            ];
        }, $media);
    }
}
