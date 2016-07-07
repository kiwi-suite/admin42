<?php
namespace Admin42;

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
                    'media' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'media/[:referrer/[:category/]]',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Media',
                                'action' => 'index',
                                'referrer' => 'index',
                                'category' => 'default',
                            ],
                            'constraints' => [
                                'referrer' => '(index|modal)'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'upload' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => 'upload/',
                                    'defaults' => [
                                        'action' => 'upload'
                                    ],
                                ],
                            ],
                            'crop' => [
                                'type' => AngularSegment::class,
                                'options' => [
                                    'route' => 'crop/:id/:dimension/',
                                    'defaults' => [
                                        'action' => 'crop'
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type' => AngularSegment::class,
                                'options' => [
                                    'route' => 'edit/:id/',
                                    'defaults' => [
                                        'action' => 'edit'
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type' => AngularSegment::class,
                                'options' => [
                                    'route' => 'delete/',
                                    'defaults' => [
                                        'action' => 'delete'
                                    ],
                                ],
                            ],
                            'stream' => [
                                'type' => AngularSegment::class,
                                'options' => [
                                    'route' => 'stream/:id/[:dimension/]',
                                    'defaults' => [
                                        'action' => 'stream'
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
                            'job' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => 'job/',
                                    'defaults' => [
                                        'action' => 'run',
                                        'controller' => __NAMESPACE__ . '\Api\Job',
                                    ],
                                ],
                            ],
                            'notification' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => 'notification/',
                                    'defaults' => [
                                        'controller' => __NAMESPACE__ . '\Api\Notification',
                                    ]
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'list' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => 'list/',
                                            'defaults' => [
                                                'action' => 'list',
                                            ],
                                        ],
                                    ],
                                    'clear' => [
                                        'type' => Literal::class,
                                        'options' => [
                                            'route' => 'clear/',
                                            'defaults' => [
                                                'action' => 'clear',
                                            ],
                                        ],
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
