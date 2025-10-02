<?php

return [
    // List of available public templates
    // Note: preview and asset paths are relative to the public root of the site that serves assets.
    // Controllers will convert relative paths to full URLs.
    'templates' => [
        [
            'slug' => 'classic',
            'name' => 'Classic',
            'description' => 'Clean, typography-first portfolio layout.',
            'preview_image' => '/templates/classic/preview.jpg',
            'assets_base_path' => '/templates/classic',
        ],
        [
            'slug' => 'modern',
            'name' => 'Modern',
            'description' => 'Bold hero, projects-forward modern layout.',
            'preview_image' => '/templates/modern/preview.jpg',
            'assets_base_path' => '/templates/modern',
        ],
    ],
];
