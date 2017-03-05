<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42;

use Admin42\Permission\Service\PermissionFactory;

return [
    'permissions' => [

        'permission_service' => [
            'admin42' => [
                'user' => [
                    'permissions' => [
                        'route/admin/user/manage',
                        'route/admin/permission-denied',
                        'route/admin/home',
                        'route/admin/logout',
                    ],
                    'options' => [
                        'redirect_after_login' => 'admin/user/manage',
                        'redirect_after_login_params' => [],
                        'assignable'           => false,
                    ],
                ],
                'admin' => [
                    'inherit_from' => 'user',
                    'permissions' => [
                        'route/admin*',
                        'dynamic/manage*',
                    ],
                    'options' => [
                        'assignable'           => true,
                    ],
                ],
                'guest' => [
                    'permissions' => [
                        'route/admin/login',
                        'route/admin/captcha',
                        'route/admin/lost-password',
                        'route/admin/recover-password',
                        'route/home',
                        'route/admin/permission-denied',
                    ],
                    'options' => [
                        'assignable'           => false,
                    ],
                ],
            ],
        ],
        'service_manager' => [
            'factories' => [
                'admin42' => PermissionFactory::class,
            ],
        ],
    ],
];
