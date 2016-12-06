<?php
namespace Admin42;

use Admin42\Authentication\AuthenticationService;
use Admin42\Authentication\Service\AuthenticationServiceFactory;
use Admin42\Crud\Service\CrudOptionsPluginManager;
use Admin42\Crud\Service\CrudOptionsPluginManagerFactory;
use Admin42\Crud\Service\EventManagerFactory as CrudEventManagerFactory;
use Admin42\Middleware\AdminMiddleware;
use Admin42\Middleware\Service\AdminMiddlewareFactory;
use Admin42\Session\Service\SessionConfigFactory;
use Admin42\Session\Service\SessionManagerFactory;

return [
    'service_manager' => [
        'factories' => [
            AuthenticationService::class    => AuthenticationServiceFactory::class,

            CrudOptionsPluginManager::class => CrudOptionsPluginManagerFactory::class,

            'Admin42\Crud\EventManager'     => CrudEventManagerFactory::class,

            AdminMiddleware::class          => AdminMiddlewareFactory::class,

            'Admin42\SessionConfig'         => SessionConfigFactory::class,
            'Admin42\SessionManager'        => SessionManagerFactory::class,
        ]
    ],
];

