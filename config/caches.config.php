<?php
namespace Admin42;

return [
    'cache' => [
        'caches' => [
            'link' => [
                'driver' => (DEVELOPMENT_MODE === true) ? 'development' : 'production',
                'namespace' =>  'link',
            ],
        ],
    ],
];
