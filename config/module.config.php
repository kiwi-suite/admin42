<?php
namespace Admin42;

use Admin42\Link\Adapter\ExternLink;

return [
    'view_manager' => [
        'display_not_found_reason'  => false,
        'display_exceptions'        => false,
        'not_found_template'        => 'admin/error/404',
        'exception_template'        => 'admin/error/index',
        'template_map'              => [
            'admin/layout/layout'       => __DIR__ . '/../view/layout/layout.phtml',
            'admin/layout/layout-min'   => __DIR__ . '/../view/layout/layout-min.phtml',
            'admin/layout/dialog'       => __DIR__ . '/../view/layout/dialog.phtml',
            'admin/error/404'           => __DIR__ . '/../view/error/404.phtml',
            'admin/error/index'         => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'       => [
            __NAMESPACE__               => __DIR__ . '/../view',
        ],
        'strategies'                => [
            'ViewJsonStrategy',
        ],
    ],

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
            'link'             => \Admin42\View\Helper\Service\LinkFactory::class,
        ],
    ],
];
