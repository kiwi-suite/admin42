<?php

return [
    'cli' => [
        'admin-create-user' => [
            'route'                     => 'admin-create-user --email= --role='
                .' [--username=] [--password=] [--status=] [--displayName=]',
            'command-name'              => 'Admin42\User\Create',
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

        'media-regenerate-images' => [
            'route'                     => 'media-regenerate-images',
            'command-name'              => 'Admin42\Media\Regenerate',
            'description'               => 'Regenerate all Images',
            'short_description'         => 'Regenerate all Images',
        ],
    ],
];
