<?php
namespace Admin42;

use Admin42\Navigation\Filter\PermissionFilter;
use Admin42\Navigation\Filter\Service\PermissionFilterFactory;
use Admin42\Navigation\Service\ContainerFactory;

return [
    'navigation' => [
        'containers' => [
            'admin42' => [
                'content' => [
                    'label' => 'label.content',
                    'order' => 1000,
                    'pages' => []
                ],
                'setting' => [
                    'label' => 'label.settings',
                    'order' => 10000,

                    'pages' => [
                        'user' => [
                            'label' => 'label.users',
                            'route' => 'admin/user',
                            'icon' => 'fa fa-users fa-fw',
                            'order' => 10000,
                        ],
                    ]
                ]
            ],
        ],

        'service_manager' => [
            'factories' => [
                'admin42' => ContainerFactory::class,
            ],
        ],
        'filter_manager' => [
            'factories' => [
                PermissionFilter::class => PermissionFilterFactory::class
            ],
            'aliases' => [
                'adminPermission' => PermissionFilter::class,
            ]
        ],
    ],
];
