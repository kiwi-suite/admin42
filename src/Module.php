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
use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\Console\Console;
use Core42\Mvc\Environment\Environment;
use Imagine\Exception\Exception;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Config;

class Module implements
    ConfigProviderInterface,
    BootstrapListenerInterface,
    DependencyIndicatorInterface
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
            include __DIR__ . '/../config/media.config.php',
            include __DIR__ . '/../config/caches.config.php',
            include __DIR__ . '/../config/form.config.php',
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

        $e->getApplication()->getEventManager()->attach(
            MvcEvent::EVENT_ROUTE,
            function(MvcEvent $event){
                /* @var \Zend\Mvc\Application $application */
                $application    = $event->getApplication();
                $serviceManager = $application->getServiceManager();
                $eventManager   = $application->getEventManager();

                /** @var Environment $environment */
                $environment = $serviceManager->get(Environment::class);

                if (!$environment->is(Module::ENVIRONMENT_ADMIN)) {
                    return;
                }

                $guards = $serviceManager->get('Core42\Permission')->getGuards('admin42');
                foreach ($guards as $_guard) {
                    $_guard->attach($eventManager);
                }

                $serviceManager->get('MvcTranslator')->setLocale('en-US');


                $viewHelperManager = $serviceManager->get('ViewHelperManager');

                $config = $serviceManager->get('config');
                $smConfig = new Config($config['admin']['view_helpers']);

                $smConfig->configureServiceManager($viewHelperManager);

                $headScript = $viewHelperManager->get('headScript');
                $headLink = $viewHelperManager->get('headLink');
                $basePath = $viewHelperManager->get('basePath');

                $headScript->appendFile($basePath('/assets/admin/core/js/vendor.min.js'));
                $headScript->appendFile($basePath('/assets/admin/core/js/admin42.min.js'));

                $headLink->appendStylesheet($basePath('/assets/admin/core/css/admin42.min.css'));

                $formElement = $viewHelperManager->get('formElement');
                $formElement->addClass('Admin42\FormElements\FileSelect', 'formfileselect');
                $formElement->addClass('Admin42\FormElements\Wysiwyg', 'formwysiwyg');
                $formElement->addClass('Admin42\FormElements\YouTube', 'formyoutube');
                $formElement->addClass('Admin42\FormElements\Link', 'formlink');
                $formElement->addClass('Admin42\FormElements\Tags', 'fromtags');
                $formElement->addClass('Admin42\FormElements\GoogleMap', 'formgooglemap');
            },
            999999
        );

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
}
