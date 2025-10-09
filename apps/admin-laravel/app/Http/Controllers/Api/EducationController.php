<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EducationController extends Controller
{
    /**
     * Get all educations ordered by sort_order and date.
     */
    public function index(): JsonResponse
    {
        $educations = Education::with(['user:id,name', 'skills:id,name'])
            ->ordered()
            ->get()
            ->map(function ($education) {
                return $this->formatEducation($education);
            });

        return response()->json([
            'success' => true,
            'data' => $educations,
        ]);
    }

    /**
     * Get a single education by ID.
     */
    public function show(int $id): JsonResponse
    {
        $education = Education::with(['user:id,name', 'skills:id,name'])->find($id);

        if (!$education) {
            return response()->json([
                'success' => false,
                'message' => 'Education not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatEducation($education),
        ]);
    }

    /**
     * Get only current (ongoing/expected) educations.
     */
    public function current(): JsonResponse
    {
        $educations = Education::with(['user:id,name', 'skills:id,name'])
            ->current()
            ->ordered()
            ->get()
            ->map(function ($education) {
                return $this->formatEducation($education);
            });

        return response()->json([
            'success' => true,
            'data' => $educations,
        ]);
    }

    /**
     * Format education data for API response.
     */
    private function formatEducation(Education $education): array
    {
        return [
            'id' => $education->id,
            'school' => $education->school,
            'degree' => $education->degree,
            'field_of_study' => $education->field_of_study,
            'is_current' => $education->is_current,
            'start_date' => $education->start_date?->format('Y-m-d'),
            'start_date_formatted' => $education->start_date?->format('M Y'),
            'end_date' => $education->end_date?->format('Y-m-d'),
            'end_date_formatted' => $education->end_date?->format('M Y') ?? 'Present',
            'duration' => $this->calculateDuration($education->start_date, $education->end_date),
            'grade' => $education->grade,
            'activities_and_societies' => $education->activities_and_societies,
            'description' => $education->description,
            'skills' => $education->skills?->pluck('name')->values() ?? [],
            'media' => $this->formatMedia($education->media),
            'sort_order' => $education->sort_order,
            'created_at' => $education->created_at?->toIso8601String(),
            'updated_at' => $education->updated_at?->toIso8601String(),
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
