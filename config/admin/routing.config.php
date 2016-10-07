<?php
namespace Admin42;

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
                        'controller' => __NAMESPACE__ . '\User',
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
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'home',
                            ],
                        ],
                    ],
                    'permission-denied' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'permission-denied/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'permission-denied',
                            ],
                        ],
                    ],
                    'login' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'login/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'lost-password' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'lost-password/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'lost-password',
                            ],
                        ],
                    ],
                    'recover-password' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'recover-password/:email/:hash/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'recover-password',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'logout/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'logout',
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'user/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'index'
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
                    'file-dialog' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'file-dialog/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\FileDialog',
                                'action' => 'fileDialog'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'link' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'link/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Link',
                                'action' => 'save'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'api' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => 'api/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Api\Api',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'tag-suggest' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => 'tag-suggest',
                                    'defaults' => [
                                        'action' => 'tagSuggest'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
