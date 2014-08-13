<?php
namespace Admin42;

return array(
    'permissions' => array(

        'service' => array(
            'admin42' => array(
                'identity_role_provider' => 'Admin42\IdentityRoleProvider',

                'guest_role' => 'guest',

                'role_provider' => array(
                    'name' => 'InMemoryRoleProvider',
                    'options' => array(
                        'admin' => array(
                            'permissions' => array(
                                'route/admin*'
                            ),
                        ),
                        'guest' => array(
                            'permissions' => array(
                                'route/admin/login'
                            ),
                        ),
                    ),
                ),

                'guards' => array(
                    'RouteGuard' => array(),
                ),

                'redirect_strategy' => array(
                    'redirect_when_connected' => false,
                    'redirect_to_route_connected' => 'home',
                    'redirect_to_route_disconnected' => 'admin/login',
                    'append_previous_uri' => true,
                    'previous_uri_query_key' => 'redirectTo'
                ),
            ),
        ),
    ),
);
