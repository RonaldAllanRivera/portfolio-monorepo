<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Get all projects ordered by sort_order and date.
     */
    public function index(): JsonResponse
    {
        $projects = Project::with([
                'user:id,name',
                'skills:id,name',
                'links:id,label,url,type',
                'experience:id,company_name',
            ])
            ->ordered()
            ->get()
            ->map(function ($project) {
                return $this->formatProject($project);
            });

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    /**
     * Get a single project by ID.
     */
    public function show(int $id): JsonResponse
    {
        $project = Project::with([
                'user:id,name',
                'skills:id,name',
                'links:id,label,url,type',
                'experience:id,company_name',
            ])->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatProject($project),
        ]);
    }

    /**
     * Get only current projects.
     */
    public function current(): JsonResponse
    {
        $projects = Project::with([
                'user:id,name',
                'skills:id,name',
                'links:id,label,url,type',
                'experience:id,company_name',
            ])
            ->current()
            ->ordered()
            ->get()
            ->map(function ($project) {
                return $this->formatProject($project);
            });

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    /**
     * Format project data for API response.
     */
    private function formatProject(Project $project): array
    {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'is_current' => $project->is_current,
            'start_date' => $project->start_date?->format('Y-m-d'),
            'start_date_formatted' => $project->start_date?->format('M Y'),
            'end_date' => $project->end_date?->format('Y-m-d'),
            'end_date_formatted' => $project->end_date?->format('M Y') ?? 'Present',
            'duration' => $this->calculateDuration($project->start_date, $project->end_date),
            'experience' => $project->experience ? [
                'id' => $project->experience->id,
                'company_name' => $project->experience->company_name,
            ] : null,
            'skills' => $project->skills?->pluck('name')->values() ?? [],
            'links' => $project->links?->map(function ($link) {
                return [
                    'id' => $link->id,
                    'label' => $link->label,
                    'url' => $link->url,
                    'type' => $link->type,
                ];
            })->values() ?? [],
            'media' => $this->formatMedia($project->media),
            'sort_order' => $project->sort_order,
            'created_at' => $project->created_at?->toIso8601String(),
            'updated_at' => $project->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Format media URLs for public access.
     */
    private function formatMedia(?array $media): array
    {
        if (!$media) {
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

    /**
     * Calculate duration between two dates.
     */
    private function calculateDuration(?\DateTime $startDate, ?\DateTime $endDate): string
    {
        if (!$startDate) {
            return '';
        }

        $end = $endDate ?? now();
        $diff = $startDate->diff($end);

        $years = $diff->y;
        $months = $diff->m;

        if ($years === 0 && $months === 0) {
            return 'Less than a month';
        }

        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ' ' . ($years === 1 ? 'year' : 'years');
        }
        if ($months > 0) {
            $parts[] = $months . ' ' . ($months === 1 ? 'month' : 'months');
        }

        return implode(' ', $parts);
    }
}
