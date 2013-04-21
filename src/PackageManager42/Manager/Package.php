<?php
namespace PackageManager42\Manager;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use PackageManager42\Router\Router;
use Zend\Mvc\MvcEvent;

class Package implements ListenerAggregateInterface
{
    /**
     * 
     * @var array
     */
    protected $listeners = array();
    
    /**
     * 
     * @var Router
     */
    protected $route;
    
    public function __construct()
    {
        $this->route = new Router();
    }
    
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE, array($this, 'onLoadModule'));
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }
    
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function onLoadModule(ModuleEvent $e)
    {
        if (!($e->getModule() instanceof PackageManagerAwareInterface)) {
            return;
        }
        $routes = $e->getModule()->getPackageRouteConfig();
        if ($routes !== null) {
            $this->route->appendRoute($routes);
        }
        
    }
    
    public function attachAdminRoutes(MvcEvent $e)
    {
        $adminRoutes = $this->route->getRoutes($e->getApplication()->getServiceManager()->get("ApplicationConfig"), $e->getRouter()->getRoutePluginManager());
        $e->getRouter()->addRoute('admin', $adminRoutes);
    }
    
    public function onRoute(MvcEvent $e)
    {
        $applicationConfig = $e->getApplication()->getServiceManager()->get('ApplicationConfig');
        
        $routeMatch = $e->getRouteMatch();
        if (in_array($routeMatch->getParam('package'), $applicationConfig['package_options']['packages'])) {
            $e->getViewModel()->setTemplate("layout/admin");
        }
    }
}
