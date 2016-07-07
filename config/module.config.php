<?php
namespace Admin42;

use Admin42\Authentication\Service\AuthenticationServiceFactory;
use Admin42\Crud\Service\CrudOptionsPluginManagerFactory;
use Admin42\Crud\Service\EventManagerFactory as CrudEventManagerFactory;
use Admin42\Media\Service\EventManagerFactory as MediaEventManagerFactory;
use Admin42\Imagine\Service\ImagineFactory;
use Admin42\Link\Adapter\Service\MediaLinkFactory;
use Admin42\Link\Service\LinkProviderFactory;
use Admin42\Media\Service\MediaOptionsFactory;
use Admin42\Media\Service\MediaUrlFactory;
use Admin42\Navigation\Listener\RbacListenerFactory;
use Admin42\Permission\Rbac\Service\IdentityRoleProviderFactory;

return [
    'view_manager' => [
        'display_not_found_reason'  => false,
        'display_exceptions'        => false,
        'not_found_template'        => 'admin/error/404',
        'exception_template'        => 'admin/error/index',
        'template_map'              => [
            'admin/layout/layout'       => __DIR__ . '/../view/layout/layout.phtml',
            'admin/layout/layout-min'   => __DIR__ . '/../view/layout/layout-min.phtml',
            'admin/layout/dialog'       => __DIR__ . '/../view/layout/dialog.phtml',
            'admin/error/404'           => __DIR__ . '/../view/error/404.phtml',
            'admin/error/index'         => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'       => [
            __NAMESPACE__               => __DIR__ . '/../view',
        ],
        'strategies'                => [
            'ViewJsonStrategy',
        ],
    ],

    'migration' => [
        'directory'     => [
            __NAMESPACE__ => __DIR__ . '/../data/migrations'
        ],
    ],

    'media_url' => '/media',

    'service_manager' => [
        'invokables' => [
            'Admin42\Link\ExternLink' => 'Admin42\Link\Adapter\ExternLink',

        ],
        'factories' => [
            'Admin42\Authentication'    => AuthenticationServiceFactory::class,

            'Admin42\IdentityRoleProvider' => IdentityRoleProviderFactory::class,

            'Admin42\Navigation\Listener\RbacListener' => RbacListenerFactory::class,

            'Imagine' => ImagineFactory::class,

            'Admin42\MediaOptions' => MediaOptionsFactory::class,

            'Admin42\LinkProvider' => LinkProviderFactory::class,
            'Admin42\Link\MediaLink' => MediaLinkFactory::class,

            'Admin42\CrudOptionsPluginManager' => CrudOptionsPluginManagerFactory::class,
            'Admin42\MediaUrl'         => MediaUrlFactory::class,

            'Admin42\Crud\EventManager' => CrudEventManagerFactory::class,
            'Admin42\Media\EventManager' => MediaEventManagerFactory::class,
        ],
    ],

    'form_elements' => [
        'invokables' => [
            'fileSelect' => 'Admin42\FormElements\FileSelect',
            'datetime'   => 'Admin42\FormElements\DateTime',
            'date'       => 'Admin42\FormElements\Date',
            //'file'       => 'Admin42\FormElements\File',
            'tags'       => 'Admin42\FormElements\Tags',
            'wysiwyg'    => 'Admin42\FormElements\Wysiwyg',
            'youtube'    => 'Admin42\FormElements\YouTube',
            'googlemap'  => 'Admin42\FormElements\GoogleMap',
        ],
        'factories' => [
            'role'      => 'Admin42\FormElements\Service\RoleFactory',
            'dynamic'   => 'Admin42\FormElements\Service\DynamicFactory',
            'link'      => 'Admin42\FormElements\Service\LinkFactory',
            'country'   => 'Admin42\FormElements\Service\CountryFactory',
        ],
    ],

    'link' => [
        'adapter' => [
            'extern' => 'Admin42\Link\ExternLink',
            'media'  => 'Admin42\Link\MediaLink',
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'media'            => 'Admin42\View\Helper\Service\MediaFactory',
            'link'             => 'Admin42\View\Helper\Service\LinkFactory',
            'mediaUrl'         => 'Admin42\View\Helper\Service\MediaUrlFactory',
        ],
    ],
];
