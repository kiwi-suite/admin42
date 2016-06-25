<?php

return [
    'navigation' => [
        'containers' => [
            'admin42' => [
                'content' => [
                    'options' => [
                        'label' => 'label.content',
                        'order' => 1000,
                        'permission' => 'navigation/section/content',
                    ],
                    'pages' => [
                        'media' => [
                            'options' => [
                                'label' => 'label.media',
                                'route' => 'admin/media',
                                'icon' => 'fa fa-picture-o fa-fw',
                                'order' => 5000,
                                'permission' => 'route/admin/media'
                            ],
                        ],
                    ]
                ],
                'setting' => [
                    'options' => [
                        'label' => 'label.settings',
                        'order' => 10000,
                        'permission' => 'navigation/section/settings',
                    ],
                    'pages' => [
                        'user' => [
                            'options' => [
                                'label' => 'label.users',
                                'route' => 'admin/user',
                                'icon' => 'fa fa-users fa-fw',
                                'order' => 10000,
                                'permission' => 'route/admin/user'
                            ],
                        ],
                    ]
                ]
            ],
        ],
        'listeners' => [
            'admin42' => [
                'Admin42\Navigation\Listener\RbacListener'
            ],
        ],
    ],
];
