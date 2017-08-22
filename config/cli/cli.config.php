<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */

namespace Admin42;

use Admin42\Command\User\CreateCommand;
use Admin42\Command\User\EnsureCommand;
use Admin42\Command\User\RecoverPasswordCommand;

return [
    'cli' => [
        'admin-create-user' => [
            'group'                     => '*',
            'route'                     => 'admin-create-user --email= --role='
                . ' [--username=] [--password=] [--status=] [--displayName=]',
            'command-name'              => CreateCommand::class,
            'description'               => 'Creates an admin user',
            'short_description'         => 'Creates an admin user',
            'defaults' => [
                'status' => 'active',
            ],
            'options_descriptions'      => [
                '--email'           => 'email of the user. required for some functionality (e.g. forgotten password)',
                '--role'            => 'role for the user',
                '--username'        => 'username for the user. none given, login is only available through email',
                '--password'        => 'password for the given user - if none give, password will be auto-generated',
                '--status'          => 'status can be active or inactive. Default:active',
                '--displayName'     => 'set the display name of the user',
            ],
        ],
        'admin-reset-password' => [
            'group'                     => '*',
            'route'                     => 'admin-reset-password --email= --password=',
            'command-name'              => RecoverPasswordCommand::class,
            'description'               => 'Resets an admin user password',
            'short_description'         => 'Resets an admin user password',
            'options_descriptions'      => [
                '--email'           => 'email of the user',
                '--password'        => 'new password for the given user',
            ],
        ],
        'admin-ensure-user' => [
            'group'                     => '*',
            'route'                     => 'admin-ensure-user --email= --role= --password='
                . ' [--username=] [--status=] [--displayName=]',
            'command-name'              => EnsureCommand::class,
            'description'               => 'Ensures an admin user exists',
            'short_description'         => 'Ensures an admin user exists',
            'defaults' => [
                'status' => 'active',
            ],
            'options_descriptions'      => [
                '--email'           => 'email of the user. required for some functionality (e.g. forgotten password)',
                '--role'            => 'role for the user',
                '--username'        => 'username for the user',
                '--password'        => 'password for the given user',
                '--status'          => 'status can be active or inactive',
                '--displayName'     => 'set the display name of the user',
            ],
        ],
    ],
];
