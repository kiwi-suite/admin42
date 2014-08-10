<?php

return array(
    'cli' => array(
        'create-user' => array(
            'route'                     => 'create-user --email='
                .' [--password=] [--username=] [--status=] [--displayName=]',
            'command-name'              => 'Admin42\User\Create',
            'description'               => 'Creates an user',
            'short_description'         => 'Creates an user',
            'defaults' => array(
                'status' => 'active',
            ),
            'options_descriptions'      => array(
                '--email'           => 'email of the user. required for some functionality (e.g. forgotten password)',
                '--username'        => 'username for the user. none given, login is only available through email',
                '--password'        => 'password for the given user - if none give, password will be auto-generated',
                '--status'          => 'status can be active or inactive. Default:active',
                '--displayName'     => 'set the display name of the user'
            ),
        ),
    ),
);
