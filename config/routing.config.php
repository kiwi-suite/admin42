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
                            'index-sidebar' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route' => 'index-sidebar/',
                                    'defaults' => array(
                                        'action' => 'index-sidebar',
                                    ),
                                ),
                            ),

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
                                'type' => 'Zend\Mvc\Router\Http\Segment',
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
