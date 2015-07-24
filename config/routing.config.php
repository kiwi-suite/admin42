<?php
namespace Admin42;

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/admin[/]',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\User',
                        'action' => 'dashboard',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'login/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'lost-password' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'lost-password/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'lost-password',
                            ),
                        ),
                    ),
                    'recover-password' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => 'recover-password/:email/:hash/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'recover-password',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'logout/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'user' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'user/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\User',
                                'action' => 'index'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'manage' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => 'manage/',
                                    'defaults' => array(
                                        'action' => 'manage',
                                    ),
                                ),
                            ),

                            'edit' => array(
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => array(
                                    'route' => 'edit/:id/',
                                    'defaults' => array(
                                        'action' => 'detail',
                                        'isEditMode' => true,
                                    ),
                                ),
                            ),
                            'add' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => 'add/',
                                    'defaults' => array(
                                        'action' => 'detail',
                                        'isEditMode' => false,
                                    ),
                                ),
                            ),

                            'delete' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => 'delete/',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'media' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => 'media/[:referrer/]',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Media',
                                'action' => 'index',
                                'referrer' => 'index'
                            ),
                            'constraints' => [
                                'referrer' => '(index|modal)'
                            ],
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'upload' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => 'upload/',
                                    'defaults' => array(
                                        'action' => 'upload'
                                    ),
                                ),
                            ),
                            'crop' => array(
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => array(
                                    'route' => 'crop/:id/:dimension/',
                                    'defaults' => array(
                                        'action' => 'crop'
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => array(
                                    'route' => 'edit/:id/',
                                    'defaults' => array(
                                        'action' => 'edit'
                                    ),
                                ),
                            ),
                            'stream' => array(
                                'type' => 'Core42\Mvc\Router\Http\AngularSegment',
                                'options' => array(
                                    'route' => 'stream/:id/[:dimension/]',
                                    'defaults' => array(
                                        'action' => 'stream'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'file-dialog' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'file-dialog/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\FileDialog',
                                'action' => 'fileDialog'
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'link' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'link/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Link',
                                'action' => 'save'
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'api' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => 'api/',
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'notification' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => 'notification/',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Api\Notification',
                                    )
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'list' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Literal',
                                        'options' => array(
                                            'route' => 'list/',
                                            'defaults' => array(
                                                'action' => 'list',
                                            ),
                                        ),
                                    ),
                                    'clear' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Literal',
                                        'options' => array(
                                            'route' => 'clear/',
                                            'defaults' => array(
                                                'action' => 'clear',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
