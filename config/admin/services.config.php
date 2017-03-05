<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

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
        ],
    ],
];
