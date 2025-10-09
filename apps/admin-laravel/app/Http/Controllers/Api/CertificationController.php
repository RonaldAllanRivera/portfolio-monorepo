<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    /**
     * List certifications ordered by sort_order then issue_date desc.
     */
    public function index(): JsonResponse
    {
        $items = Certification::with(['user:id,name', 'organization:id,name,website', 'skills:id,name'])
            ->ordered()
            ->get()
            ->map(fn ($c) => $this->formatCertification($c));

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
        $cert = Certification::with(['user:id,name', 'organization:id,name,website', 'skills:id,name'])->find($id);

        if (! $cert) {
            return response()->json([
                'success' => false,
                'message' => 'Certification not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatCertification($cert),
        ]);
    }

    /**
     * Non-expired certifications (or no expiration set).
     */
    public function current(): JsonResponse
    {
        $today = now()->startOfDay();

        $items = Certification::with(['user:id,name', 'organization:id,name,website', 'skills:id,name'])
            ->where(function ($q) use ($today) {
                $q->whereNull('expiration_date')
                  ->orWhereDate('expiration_date', '>=', $today);
            })
            ->ordered()
            ->get()
            ->map(fn ($c) => $this->formatCertification($c));

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Normalize a certification for API responses.
     */
    private function formatCertification(Certification $c): array
    {
        $isExpired = $c->expiration_date !== null && $c->expiration_date->isBefore(now()->startOfDay());

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
            'skills' => $c->skills?->pluck('name')->values() ?? [],
            'media' => $this->formatMedia($c->media),
            'sort_order' => $c->sort_order,
            'created_at' => $c->created_at?->toIso8601String(),
            'updated_at' => $c->updated_at?->toIso8601String(),
        ];
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
