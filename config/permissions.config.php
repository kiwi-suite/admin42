<?php
namespace Admin42;

use Admin42\Permission\Rbac\Identity\IdentityRoleProvider;

return [
    'permissions' => [

        'service' => [
            'admin42' => [
                'identity_role_provider' => IdentityRoleProvider::class,

                'guest_role' => 'guest',

                'role_provider' => [
                    'name' => 'InMemoryRoleProvider',
                    'options' => [
                        'user' => [
                            'permissions' => [
                                'route/admin/user/manage',
                                'route/admin/home',
                                'route/admin/logout'
                            ],
                            'options' => [
                                'redirect_after_login' => 'admin/user/manage',
                            ]
                        ],
                        'admin' => [
                            'children' => [
                                'user'
                            ],
                            'permissions' => [
                                'route/admin*',
                                'navigation/section*',
                                'dynamic/manage*',

                            ],
                            'options' => [
                                'redirect_after_login' => 'admin/user/manage',
                            ]
                        ],
                        'guest' => [
                            'permissions' => [
                                'route/admin/login',
                                'route/admin/lost-password',
                                'route/admin/recover-password',
                                'route/home',
                                'route/admin/api/job',
                            ],
                            'options' => []
                        ],
                    ],
                ],

                'guards' => [
                    'RouteGuard' => [
                        'protected_route' => 'admin'
                    ],
                ],

                'redirect_strategy' => [
                    'redirect_when_connected' => false,
                    'redirect_to_route_connected' => 'home',
                    'redirect_to_route_disconnected' => 'admin/login',
                    'append_previous_uri' => true,
                    'previous_uri_query_key' => 'redirectTo'
                ],
            ],
        ],
    ],
];
