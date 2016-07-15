<?php
namespace Admin42;

use Admin42\Authentication\AuthenticationService;
use Admin42\Authentication\Service\AuthenticationServiceFactory;
use Admin42\Crud\Service\CrudOptionsPluginManager;
use Admin42\Crud\Service\CrudOptionsPluginManagerFactory;
use Admin42\Crud\Service\EventManagerFactory as CrudEventManagerFactory;
use Admin42\Imagine\Service\ImagineFactory;
use Admin42\Link\Adapter\ExternLink;
use Admin42\Link\Adapter\MediaLink;
use Admin42\Link\Adapter\Service\MediaLinkFactory;
use Admin42\Link\LinkProvider;
use Admin42\Link\Service\LinkProviderFactory;
use Admin42\Media\MediaOptions;
use Admin42\Media\MediaUrl;
use Admin42\Media\Service\EventManagerFactory as MediaEventManagerFactory;
use Admin42\Media\Service\MediaOptionsFactory;
use Admin42\Media\Service\MediaUrlFactory;
use Admin42\Navigation\Listener\RbacListenerFactory;
use Admin42\Permission\Rbac\Identity\IdentityRoleProvider;
use Admin42\Permission\Rbac\Service\IdentityRoleProviderFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            AuthenticationService::class        => AuthenticationServiceFactory::class,

            IdentityRoleProvider::class     => IdentityRoleProviderFactory::class,

            'Admin42\Navigation\Listener\RbacListener' => RbacListenerFactory::class,

            'Imagine'                       => ImagineFactory::class,

            MediaOptions::class             => MediaOptionsFactory::class,

            LinkProvider::class             => LinkProviderFactory::class,
            ExternLink::class               => InvokableFactory::class,
            MediaLink::class                => MediaLinkFactory::class,

            CrudOptionsPluginManager::class => CrudOptionsPluginManagerFactory::class,
            MediaUrl::class                 => MediaUrlFactory::class,

            'Admin42\Crud\EventManager'     => CrudEventManagerFactory::class,
            'Admin42\Media\EventManager'    => MediaEventManagerFactory::class,
        ],
    ],
];

