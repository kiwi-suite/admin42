<?php
return [
    'caches' => [
        'Cache\Media' => [
            'adapter' => [
                'name' => 'memory',
            ],
            'plugins' => [
                'Serializer'
            ],
        ],
        'Cache\Link' => [
            'adapter' => [
                'name' => 'memory',
            ],
            'plugins' => [
                'Serializer'
            ],
        ],
    ],
];
