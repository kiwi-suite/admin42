<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42;

use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\Console\Console;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

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
            include __DIR__ . '/../../config/translation.config.php',
            include __DIR__ . '/../../config/admin.config.php',
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
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController',
            MvcEvent::EVENT_DISPATCH,
            function ($e) {
                $controller      = $e->getTarget();

                if (!($controller instanceof AbstractAdminController)) {
                    return;
                }

                $controller->layout()->setTemplate("admin/layout/layout");

                $sm = $e->getApplication()->getServiceManager();
                $sm->get('Core42\Form\ThemeManager')->setDefaultThemeName(strtolower(__NAMESPACE__));

                $sm->get('MvcTranslator')->setLocale('en-US');

                $viewHelperManager = $sm->get('viewHelperManager');

                $headScript = $viewHelperManager->get('headScript');
                $headLink = $viewHelperManager->get('headLink');
                $basePath = $viewHelperManager->get('basePath');

                $headScript->appendFile($basePath('/assets/admin/core/js/raum42-admin.min.js'));
                $headScript->appendFile($basePath('/assets/admin/core/ckeditor/ckeditor.js'));
                $headScript->appendFile($basePath('/assets/admin/core/ckeditor/adapters/jquery.js'));
                $headLink->appendStylesheet($basePath('/assets/admin/core/css/raum42-admin.min.css'));
            },
            100
        );

        if (Console::isConsole()) {
            return;
        }

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
