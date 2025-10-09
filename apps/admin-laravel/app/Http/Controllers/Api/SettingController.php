<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::ordered()->get()->map(fn ($s) => $this->formatSetting($s));

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    public function current(): JsonResponse
    {
        $setting = Setting::ordered()->first();

        if (!$setting) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSetting($setting),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatSetting($setting),
        ]);
    }

    private function formatSetting(Setting $s): array
    {
        $logoUrl = $s->logo ? Storage::disk('r2')->url($s->logo) : null;
        $faviconUrl = $s->favicon ? Storage::disk('r2')->url($s->favicon) : null;
        $profileUrl = $s->profile_picture ? Storage::disk('r2')->url($s->profile_picture) : null;

        return [
            'id' => $s->id,
            'user_id' => $s->user_id,

            // Content
            'headline' => $s->headline,
            'about_me' => $s->about_me,

            // Media
            'logo' => $s->logo,
            'logo_url' => $logoUrl,
            'favicon' => $s->favicon,
            'favicon_url' => $faviconUrl,
            'profile_picture' => $s->profile_picture,
            'profile_picture_url' => $profileUrl,

            // Appearance
            'active_public_template' => $s->active_public_template,
            'brand_primary_color' => $s->brand_primary_color,
            'brand_secondary_color' => $s->brand_secondary_color,

            // SEO
            'seo_title' => $s->seo_title,
            'seo_description' => $s->seo_description,
            'seo_keywords' => $s->seo_keywords,

            // Contact
            'contact_email' => $s->contact_email,
            'contact_phone' => $s->contact_phone,
            'contact_whatsapp' => $s->contact_whatsapp,

            // Socials
            'github_url' => $s->github_url,
            'linkedin_url' => $s->linkedin_url,
            'twitter_url' => $s->twitter_url,
            'youtube_url' => $s->youtube_url,
            'dribbble_url' => $s->dribbble_url,
            'behance_url' => $s->behance_url,

            // Personal
            'date_of_birth' => $s->date_of_birth?->format('Y-m-d'),
            'gender' => $s->gender,
            'marital_status' => $s->marital_status,
            'nationality' => $s->nationality,

            // Address
            'address_line1' => $s->address_line1,
            'address_line2' => $s->address_line2,
            'address_city' => $s->address_city,
            'address_state' => $s->address_state,
            'address_postal_code' => $s->address_postal_code,
            'address_country' => $s->address_country,

            // Availability
            'open_to_work' => (bool) $s->open_to_work,
            'hourly_rate' => $s->hourly_rate,
            'preferred_roles' => $s->preferred_roles,

            'sort_order' => $s->sort_order,
            'created_at' => $s->created_at?->toIso8601String(),
            'updated_at' => $s->updated_at?->toIso8601String(),
        ];
    }
}
