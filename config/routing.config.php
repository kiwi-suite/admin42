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
                ),
            ),
        ),
    ),
);
