<?php
namespace Admin42;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../../config/module.config.php',
            include __DIR__ . '/../../config/cli.config.php',
            include __DIR__ . '/../../config/permissions.config.php',
            include __DIR__ . '/../../config/assets.config.php',
            include __DIR__ . '/../../config/form.config.php',
            include __DIR__ . '/../../config/navigation.config.php',
            include __DIR__ . '/../../config/project.config.php',
            include __DIR__ . '/../../config/routing.config.php'
        );
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller      = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

            if (__NAMESPACE__ !== $moduleNamespace) {
                return;
            }

            $sm = $e->getApplication()->getServiceManager();
            $sm->get('Core42\Form\ThemeManager')->setDefaultThemeName(strtolower(__NAMESPACE__));
        }, 100);

        /* @var \Zend\Mvc\Application $application */
        $application    = $e->getTarget();
        $serviceManager = $application->getServiceManager();
        $eventManager   = $application->getEventManager();

        $guards = $serviceManager->get('Core42\Permission')->getGuards('admin42');
        foreach ($guards as $_guard) {
            $eventManager->attachAggregate($_guard);
        }
    }
}
