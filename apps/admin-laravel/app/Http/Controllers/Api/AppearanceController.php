<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AppearanceController extends Controller
{
    /**
     * Public endpoint to retrieve current appearance configuration
     */
    public function show(): JsonResponse
    {
        $setting = Setting::ordered()->first();

        if (!$setting) {
            return response()->json([
                'success' => true,
                'data' => [
                    'active_public_template' => config('app.default_public_template', 'classic'),
                    'brand_logo_url' => null,
                    'favicon_url' => null,
                    'brand_primary_color' => null,
                    'brand_secondary_color' => null,
                    'seo_meta' => [
                        'title' => config('app.name', 'Portfolio'),
                        'description' => null,
                        'image_url' => null,
                    ],
                ],
            ]);
        }

        $logoUrl = $setting->logo ? Storage::disk('r2')->url($setting->logo) : null;
        $faviconUrl = $setting->favicon ? Storage::disk('r2')->url($setting->favicon) : null;

        $data = [
            'active_public_template' => $setting->active_public_template ?? config('app.default_public_template', 'classic'),
            'brand_logo_url' => $logoUrl,
            'favicon_url' => $faviconUrl,
            'brand_primary_color' => $setting->brand_primary_color ?? null,
            'brand_secondary_color' => $setting->brand_secondary_color ?? null,
            'seo_meta' => [
                'title' => $setting->seo_title ?? config('app.name', 'Portfolio'),
                'description' => $setting->seo_description,
                'image_url' => $logoUrl,
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
