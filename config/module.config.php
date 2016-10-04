<?php
namespace Admin42;

use Admin42\Link\Adapter\ExternalLink;
use Admin42\View\Helper\Service\LinkFactory;

return [
    'migration' => [
        'directory'     => [
            __NAMESPACE__ => __DIR__ . '/../data/migrations'
        ],
    ],

    'link' => [
        'adapter' => [
            'external' => ExternalLink::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'link'             => LinkFactory::class,
        ],
    ],
];
