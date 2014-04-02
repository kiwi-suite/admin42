<?php
namespace Admin42;

return array(
    'permission' => array(
        //whether enable or disable the permissions
        'enabled' => true,
        //default role for unauthenticated users
        'default_unauthenticated_role' => 'guest',
        //default role for authenticated users (used when no role is provided by the identity provider)
        'default_authenticated_role' => 'user',

        // provider to receive the identity of the (authenticated) user
        'identity_provider' => 'Core42\Authentication',
        // authentication service to check if the user ist authenticated
        'authentication_service' => 'Core42\Authentication',

        'role_provider' => 'Core42\Permission\Provider\Role\Array',

        'guards' => array(

        ),

        'roles' => array(
            'guest' => array(
                'options' => array(),
                'rules' => array(

                ),
            ),
            'user' => array(
                'options' => array(),
                'rules' => array(),
            ),
        ),
    ),
);
