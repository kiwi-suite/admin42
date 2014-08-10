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
                            ),
                        ),
                        'may_terminate' => false,
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
                        ),
                    ),
                ),
            ),
        ),
    ),
);
