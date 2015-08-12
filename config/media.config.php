<?php

return [
    'media' => [
        'upload_host' => '',
        'path' => 'data/media/',

        'categories' => [
            'default' => 'media.category.default'
        ],

        'images' => [
            'adapter' => 'imagick',
            'dimensions' => [
                'admin_thumbnail' => [
                    'system' => true,
                    'width' => 300,
                    'height' => 300
                ],
                'original' => [
                    'system' => true,
                    'width' => 'auto',
                    'height' => 'auto'
                ],
            ]
        ],
    ],
];
