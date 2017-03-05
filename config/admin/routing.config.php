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

use Admin42\Controller\Api\ApiController;
use Admin42\Controller\EnvironmentController;
use Admin42\Controller\LinkController;
use Admin42\Controller\UserController;
use Admin42\Middleware\AdminMiddleware;
use Core42\Mvc\Router\Http\AngularSegment;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin[/]',
                    'defaults' => [
                        'middleware' => AdminMiddleware::class,
                        'controller' => UserController::class,
                        'action' => 'dashboard',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'home' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'home/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'home',
                            ],
                        ],
                    ],
                    'permission-denied' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'permission-denied/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'permission-denied',
                            ],
                        ],
                    ],
                    'login' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'login/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'captcha' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'captcha/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'captcha',
                            ],
                        ],
                    ],
                    'lost-password' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'lost-password/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'lost-password',
                            ],
                        ],
                    ],
                    'recover-password' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'recover-password/:email/:hash/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'recover-password',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'logout/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'logout',
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'user/',
                            'defaults' => [
                                'controller' => UserController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'manage' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => 'manage/',
                                    'defaults' => [
                                        'action' => 'manage',
                                    ],
                                ],
                            ],

                            'edit' => [
                                'type' => AngularSegment::class,
                                'options' => [
                                    'route' => 'edit/:id/',
                                    'defaults' => [
                                        'action' => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => 'add/',
                                    'defaults' => [
                                        'action' => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],

                            'delete' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => 'delete/',
                                    'defaults' => [
                                        'action' => 'delete',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'environment' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'environment/',
                            'defaults' => [
                                'controller' =>  EnvironmentController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'link' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'link/',
                            'defaults' => [
                                'controller' => LinkController::class,
                                'action' => 'save',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'wysiwyg' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => 'wysiwyg/[:id/]',
                                    'defaults' => [
                                        'action' => 'wysiwyg',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'api' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'api/',
                            'defaults' => [
                                'controller' => ApiController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [

                        ],
                    ],
                ],
            ],
        ],
    ],
];
