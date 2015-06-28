<?php

return [
    'media' => [
        'path' => 'data/media/',

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
