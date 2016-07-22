<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42;

use Admin42\Authentication\AuthenticationService;
use Admin42\FormElements\Link;
use Admin42\FormElements\Tags;
use Admin42\FormElements\Wysiwyg;
use Admin42\FormElements\YouTube;
use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Core42\Console\Console;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\Mvc\MvcEvent;

class Module implements
    ConfigProviderInterface,
    BootstrapListenerInterface,
    DependencyIndicatorInterface,
    AdminAwareModuleInterface
{
    /**
     *
     */
    const ENVIRONMENT_ADMIN = 'admin';

    /**
     * @return array
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../config/module.config.php',
            include __DIR__ . '/../config/cli.config.php',
            include __DIR__ . '/../config/permissions.config.php',
            include __DIR__ . '/../config/assets.config.php',
            include __DIR__ . '/../config/navigation.config.php',
            include __DIR__ . '/../config/project.config.php',
            include __DIR__ . '/../config/translation.config.php',
            include __DIR__ . '/../config/admin.config.php',
            include __DIR__ . '/../config/caches.config.php',
            include __DIR__ . '/../config/services.config.php',
            include __DIR__ . '/../config/routing.config.php'
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
        if (Console::isConsole()) {
            return;
        }

        $adminSetup = new AdminSetup();
        $adminSetup->attach($e->getTarget()->getEventManager(), 999999);

        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController',
            MvcEvent::EVENT_DISPATCH,
            function ($e) {
                $controller      = $e->getTarget();

                $serviceManager = $e->getApplication()->getServiceManager();

                /** @var Environment $environment */
                $environment = $serviceManager->get(Environment::class);

                if (!$environment->is("admin")) {
                    return;
                }

                $controller->layout()->setTemplate("admin/layout/layout");
                $authenticationService = $serviceManager->get(AuthenticationService::class);
                if (!$authenticationService->hasIdentity()) {
                    $controller->layout()->setTemplate("admin/layout/layout-min");
                }
            },
            100
        );
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'Core42',
        ];
    }

    /**
     * @return array
     */
    public function getAdminStylesheets()
    {
        return [
            '/assets/admin/core/css/admin42.min.css'
        ];
    }

    /**
     * @return array
     */
    public function getAdminJavascript()
    {
        return [
            '/assets/admin/core/js/vendor.min.js',
            '/assets/admin/core/js/admin42.min.js',
        ];
    }

    /**
     * @return array
     */
    public function getAdminFormViewHelpers()
    {
        return [
            Wysiwyg::class,
            YouTube::class,
            Link::class,
            Tags::class,
        ];
    }
}
