<?php

// config for Awcodes/Richie
return [
    'generator' => [
        'namespace' => 'App\\Richie\\Actions',
        'views_path' => 'richie',
    ],
    'media' => [
        'accepted_file_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'application/pdf'],
        'disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
        'directory' => 'media',
        'image_resize_mode' => null,
        'image_crop_aspect_ratio' => null,
        'image_resize_target_width' => null,
        'image_resize_target_height' => null,
        'max_size' => 2042,
        'min_size' => 0,
        'preserve_file_names' => false,
        'use_relative_paths' => true,
        'visibility' => 'public',
    ],
];
