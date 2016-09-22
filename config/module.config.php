<?php
namespace Admin42;

use Admin42\Filter\ToDateTime;
use Admin42\Link\Adapter\ExternLink;
use Admin42\View\Helper\Service\LinkFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'migration' => [
        'directory'     => [
            __NAMESPACE__ => __DIR__ . '/../data/migrations'
        ],
    ],

    'link' => [
        'adapter' => [
            'extern' => ExternLink::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'link'             => LinkFactory::class,
        ],
    ],

    'filters' => [
        'factories' => [
            ToDateTime::class   => InvokableFactory::class,
        ],
        'aliases' => [
            'toDateTime'        => ToDateTime::class,
        ],
    ],
];
