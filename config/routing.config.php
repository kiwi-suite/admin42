<?php
namespace Admin42;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
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
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => [
                            'route' => 'home/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'home',
                            ],
                        ],
                    ],
                    'login' => [
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => [
                            'route' => 'login/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'lost-password' => [
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => [
                            'route' => 'lost-password/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'lost-password',
                            ],
                        ],
                    ],
                    'recover-password' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => 'recover-password/:email/:hash/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'recover-password',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => [
                            'route' => 'logout/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'logout',
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => 'Zend\Mvc\Router\Http\Literal',
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
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route' => 'manage/',
                                    'defaults' => [
                                        'action' => 'manage',
                                    ],
                                ],
                            ],

                            'edit' => [
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => [
                                    'route' => 'edit/:id/',
                                    'defaults' => [
                                        'action' => 'detail',
                                        'isEditMode' => true,
                                    ],
                                ],
                            ],
                            'add' => [
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route' => 'add/',
                                    'defaults' => [
                                        'action' => 'detail',
                                        'isEditMode' => false,
                                    ],
                                ],
                            ],

                            'delete' => [
                                'type' => 'Zend\Mvc\Router\Http\Literal',
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
                        'type' => 'Zend\Mvc\Router\Http\Segment',
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
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route' => 'upload/',
                                    'defaults' => [
                                        'action' => 'upload'
                                    ],
                                ],
                            ],
                            'crop' => [
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => [
                                    'route' => 'crop/:id/:dimension/',
                                    'defaults' => [
                                        'action' => 'crop'
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => [
                                    'route' => 'edit/:id/',
                                    'defaults' => [
                                        'action' => 'edit'
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => [
                                    'route' => 'delete/',
                                    'defaults' => [
                                        'action' => 'delete'
                                    ],
                                ],
                            ],
                            'stream' => [
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
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
                        'type' => 'Zend\Mvc\Router\Http\Literal',
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
                        'type' => 'Zend\Mvc\Router\Http\Literal',
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
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => [
                            'route' => 'api/',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Api\Api',
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'tag-suggest' => [
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route' => 'tag-suggest',
                                    'defaults' => [
                                        'action' => 'tagSuggest'
                                    ],
                                ],
                            ],
                            'job' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => 'job/',
                                    'defaults' => [
                                        'action' => 'run',
                                        'controller' => __NAMESPACE__ . '\Api\Job',
                                    ],
                                ],
                            ],
                            'notification' => [
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => [
                                    'route' => 'notification/',
                                    'defaults' => [
                                        'controller' => __NAMESPACE__ . '\Api\Notification',
                                    ]
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'list' => [
                                        'type' => 'Zend\Mvc\Router\Http\Literal',
                                        'options' => [
                                            'route' => 'list/',
                                            'defaults' => [
                                                'action' => 'list',
                                            ],
                                        ],
                                    ],
                                    'clear' => [
                                        'type' => 'Zend\Mvc\Router\Http\Literal',
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
