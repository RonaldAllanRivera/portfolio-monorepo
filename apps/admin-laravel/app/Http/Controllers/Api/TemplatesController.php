<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TemplatesController extends Controller
{
    public function index(): JsonResponse
    {
        $templates = config('templates.templates', []);

        $data = collect($templates)->map(function (array $tpl) {
            $preview = $tpl['preview_image'] ?? null;
            $assetsBase = $tpl['assets_base_path'] ?? null;

            return [
                'slug' => $tpl['slug'] ?? '',
                'name' => $tpl['name'] ?? '',
                'description' => $tpl['description'] ?? '',
                'preview_image' => $preview,
                'preview_image_url' => $preview ? url($preview) : null,
                'assets_base_path' => $assetsBase,
                'assets_base_url' => $assetsBase ? url($assetsBase) : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
