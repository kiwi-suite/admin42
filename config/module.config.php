<?php
namespace Admin42;

return array(
    'view_manager' => array(
        'display_not_found_reason'  => false,
        'display_exceptions'        => false,
        'not_found_template'        => 'admin/error/404',
        'exception_template'        => 'admin/error/index',
        'template_map'              => array(
            'admin/layout/layout'       => __DIR__ . '/../view/layout/layout.phtml',
            'admin/layout/layout-min'   => __DIR__ . '/../view/layout/layout-min.phtml',
            'admin/error/404'           => __DIR__ . '/../view/error/404.phtml',
            'admin/error/index'         => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack'       => array(
            __NAMESPACE__               => __DIR__ . '/../view',
        ),
        'strategies'                => array(
            'ViewJsonStrategy',
        ),
    ),

    'migration' => array(
        'directory'     => array(
            __NAMESPACE__ => __DIR__ . '/../data/migrations'
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Admin42\Authentication'    => 'Admin42\Authentication\Service\AuthenticationServiceFactory',

            'Admin42\IdentityRoleProvider' => 'Admin42\Permission\Rbac\Service\IdentityRoleProviderFactory',

            'Admin42\Navigation\Listener\RbacListener' => 'Admin42\Navigation\Listener\RbacListenerFactory',

            'Admin42\DynamicAttributeProvider' => 'Admin42\DynamicAttribute\Service\ProviderPluginManagerFactory',
        ),
    ),

    'form_elements' => array(
        'factories' => array(
            'role'      => 'Admin42\FormElements\Service\RoleFactory',
            'dynamic'   => 'Admin42\FormElements\Service\DynamicFactory',
        ),
    ),
);
