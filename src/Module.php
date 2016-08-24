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
use Admin42\FormElements\Date;
use Admin42\FormElements\DateTime;
use Admin42\FormElements\GoogleMap;
use Admin42\FormElements\Link;
use Admin42\FormElements\Service\CountryFactory;
use Admin42\FormElements\Service\DynamicFactory;
use Admin42\FormElements\Service\LinkFactory;
use Admin42\FormElements\Service\RoleFactory;
use Admin42\FormElements\Tags;
use Admin42\FormElements\Wysiwyg;
use Admin42\FormElements\YouTube;
use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Admin42\View\Helper\Admin;
use Admin42\View\Helper\Form\Form;
use Admin42\View\Helper\Form\FormCollection;
use Admin42\View\Helper\Form\FormElement;
use Admin42\View\Helper\Form\FormRow;
use Admin42\View\Helper\Form\FormWysiwyg;
use Admin42\View\Helper\Service\AdminFactory;
use Core42\Console\Console;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Factory\InvokableFactory;


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

        $eventManager   = $e->getApplication()->getEventManager();
        $guards = $e->getApplication()->getServiceManager()->get('Permission')->getGuards('admin42');
        foreach ($guards as $_guard) {
            $_guard->attach($eventManager);
        }

        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController',
            MvcEvent::EVENT_DISPATCH,
            function ($e) {
                $controller      = $e->getTarget();

                $serviceManager = $e->getApplication()->getServiceManager();

                /** @var Environment $environment */
                $environment = $serviceManager->get(Environment::class);

                if (!$environment->is(self::ENVIRONMENT_ADMIN)) {
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
            '/assets/admin/admin42/css/admin42.min.css'
        ];
    }

    /**
     * @return array
     */
    public function getAdminJavascript()
    {
        return [
            '/assets/admin/admin42/js/vendor.min.js',
            '/assets/admin/admin42/js/admin42.min.js',
        ];
    }

    /**
     * @return array
     */
    public function getAdminViewHelpers()
    {
        return [
            'factories'  => [
                Admin::class            => AdminFactory::class,

                Form::class             => InvokableFactory::class,
                FormCollection::class   => InvokableFactory::class,
                FormElement::class      => InvokableFactory::class,
                FormRow::class          => InvokableFactory::class,
                FormWysiwyg::class      => InvokableFactory::class,
            ],
            'aliases' => [
                'admin'                 => Admin::class,
                'form'                  => Form::class,
                'formcollection'        => FormCollection::class,
                'form_collection'       => FormCollection::class,
                'formCollection'        => FormCollection::class,
                'formelement'           => FormElement::class,
                'formElement'           => FormElement::class,
                'form_element'          => FormElement::class,
                'formrow'               => FormRow::class,
                'form_row'              => FormRow::class,
                'formRow'               => FormRow::class,
                'formwysiwyg'           => FormWysiwyg::class,
                'formWysiwyg'           => FormWysiwyg::class,
                'form_wysiwyg'          => FormWysiwyg::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAdminFormElements()
    {
        return [
            'factories' => [
                'role'              => RoleFactory::class,
                'dynamic'           => DynamicFactory::class,
                'link'              => LinkFactory::class,
                'country'           => CountryFactory::class,

                DateTime::class     => InvokableFactory::class,
                Date::class         => InvokableFactory::class,
                Tags::class         => InvokableFactory::class,
                Wysiwyg::class      => InvokableFactory::class,
                YouTube::class      => InvokableFactory::class,
                GoogleMap::class    => InvokableFactory::class,
            ],
            'aliases' => [
                'datetime'   => DateTime::class,
                'dateTime'   => DateTime::class,
                'date'       => Date::class,
                'tags'       => Tags::class,
                'wysiwyg'    => Wysiwyg::class,
                'youtube'    => YouTube::class,
                'googlemap'  => GoogleMap::class,
            ],
        ];
    }
}
