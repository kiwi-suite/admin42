<?php
namespace PackageManager42;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use PackageManager42\Manager\Package;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;

class Module implements BootstrapListenerInterface,
                            ConfigProviderInterface,
                            AutoloaderProviderInterface,
                            InitProviderInterface
{

    /**
     * 
     * @var Package
     */
    private $package = null;
    
    public function init(ModuleManagerInterface $manager)
    {
        $this->package = new Package();
        if (php_sapi_name() == 'cli') {
            return;
        }
        $this->package->attach($manager->getEventManager());
    }
    
    /*
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }    

    /*
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        if (php_sapi_name() == 'cli') {
            return;
        }
        $this->package->attachAdminRoutes($e);
        $this->package->attach($e->getApplication()->getEventManager());
    }
    
    /*
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
