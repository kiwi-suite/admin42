<?php
namespace PackageManager42\Router;

use Zend\Mvc\Router\Http\Part;
use Zend\Mvc\Router\RoutePluginManager;
class Router
{
    private $routes = array();
    
    public function appendRoute(array $routeSpec)
    {
        $this->routes = array_merge($routeSpec, $this->routes);
    }
    
    public function getRoutes(array $applicationConfig, RoutePluginManager $routePlugin)
    {
        $packageConfig = $applicationConfig['package_options'];
        
        $route = Part::factory(array(
            'route' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => $packageConfig['admin_base_url'],
                    'defaults' => array(
                        '__NAMESPACE__' => 'User42\Controller',
                        'controller' => 'User42\Controller\Auth',
                        'action' => 'login',
                        'package' => current($packageConfig['packages']),
                    ),
                ),
            ),
            'route_plugins' => $routePlugin,
            'may_terminate' => true,
            'child_routes' => array(
                'admin_package' => array(
                    'type' => 'Zend\Mvc\Router\Http\Segment',
                    'options' => array(
                        'route' => ':package[/]',
                        'constraints' => array(
                            'package' => '('.(implode('|', $packageConfig['packages'])).')'
                        ),
                        'defaults' => array(
                            '__NAMESPACE__' => 'User42\Controller',
                            'controller' => 'User42\Controller\Auth',
                            'action' => 'login',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => $this->routes,
                ),    
            ),
        ));
        return $route;
    }
}
