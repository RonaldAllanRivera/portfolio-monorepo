<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ExperienceController extends Controller
{
    /**
     * Get all experiences ordered by sort_order and date.
     */
    public function index(): JsonResponse
    {
        $experiences = Experience::with('user:id,name')
            ->ordered()
            ->get()
            ->map(function ($experience) {
                return $this->formatExperience($experience);
            });

        return response()->json([
            'success' => true,
            'data' => $experiences,
        ]);
    }

    /**
     * Get a single experience by ID.
     */
    public function show(int $id): JsonResponse
    {
        $experience = Experience::with('user:id,name')->find($id);

        if (!$experience) {
            return response()->json([
                'success' => false,
                'message' => 'Experience not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatExperience($experience),
        ]);
    }

    /**
     * Get only current experiences.
     */
    public function current(): JsonResponse
    {
        $experiences = Experience::with('user:id,name')
            ->current()
            ->ordered()
            ->get()
            ->map(function ($experience) {
                return $this->formatExperience($experience);
            });

        return response()->json([
            'success' => true,
            'data' => $experiences,
        ]);
    }

    /**
     * Format experience data for API response.
     */
    private function formatExperience(Experience $experience): array
    {
        return [
            'id' => $experience->id,
            'title' => $experience->title,
            'employment_type' => $experience->employment_type,
            'company_name' => $experience->company_name,
            'is_current' => $experience->is_current,
            'start_date' => $experience->start_date?->format('Y-m-d'),
            'start_date_formatted' => $experience->start_date?->format('M Y'),
            'end_date' => $experience->end_date?->format('Y-m-d'),
            'end_date_formatted' => $experience->end_date?->format('M Y') ?? 'Present',
            'duration' => $this->calculateDuration($experience->start_date, $experience->end_date),
            'location' => $experience->location,
            'location_type' => $experience->location_type,
            'description' => $experience->description,
            'profile_headline' => $experience->profile_headline,
            'skills' => $experience->skills ?? [],
            'media' => $this->formatMedia($experience->media),
            'sort_order' => $experience->sort_order,
            'created_at' => $experience->created_at?->toIso8601String(),
            'updated_at' => $experience->updated_at?->toIso8601String(),
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
                'url' => Storage::disk('public')->url($path),
                'full_url' => url(Storage::disk('public')->url($path)),
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
