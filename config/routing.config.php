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
                        'controller' => __NAMESPACE__ . '\Controller\User',
                        'action' => 'dashboard',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => 'login/',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\User',
                                'action' => 'login',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
