<?php
namespace Admin42;

use Admin42\Command\User\CreateCommand;

return [
    'cli' => [
        'admin-create-user' => [
            'route'                     => 'admin-create-user --email= --role='
                .' [--username=] [--password=] [--status=] [--displayName=]',
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
                '--displayName'     => 'set the display name of the user'
            ],
        ],
    ],
];
