<?php
namespace Admin42;

use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Config;
use Zend\View\HelperPluginManager;

class AdminSetup extends AbstractListenerAggregate
{
    /**
     * @var HelperPluginManager
     */
    protected $viewHelperManager;

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param int $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'setup'], $priority);
    }

    /**
     * @param MvcEvent $event
     */
    public function setup(MvcEvent $event)
    {
        $application    = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        if (!$serviceManager->get(Environment::class)->is(Module::ENVIRONMENT_ADMIN)) {
            return;
        }

        $eventManager   = $application->getEventManager();
        $guards = $serviceManager->get('Core42\Permission')->getGuards('admin42');
        foreach ($guards as $_guard) {
            $_guard->attach($eventManager);
        }

        $serviceManager->get('MvcTranslator')->setLocale('en-US');

        $this->viewHelperManager = $serviceManager->get('ViewHelperManager');
        $config = $serviceManager->get('config');
        $smConfig = new Config($config['admin']['view_helpers']);
        $smConfig->configureServiceManager($this->viewHelperManager);

        $formElementManager = $serviceManager->get("FormElementManager");
        $config = $serviceManager->get("config");
        $smConfig = new Config($config['admin']['form_elements']);
        $smConfig->configureServiceManager($formElementManager);


        /** @var ModuleManager $moduleManager */
        $moduleManager = $serviceManager->get(ModuleManager::class);

        foreach ($moduleManager->getLoadedModules() as $module) {
            if (!($module instanceof AdminAwareModuleInterface)) {
                continue;
            }

            $this->setupJavascript($module);
            $this->setupStylesheets($module);
            $this->setupFormElements($module);
        }
    }

    /**
     * @param AdminAwareModuleInterface $module
     */
    protected function setupStylesheets(AdminAwareModuleInterface $module)
    {
        $headLink = $this->viewHelperManager->get('headLink');
        $basePath = $this->viewHelperManager->get('basePath');

        $stylesheets = $module->getAdminStylesheets();
        if (!empty($stylesheets) && is_array($stylesheets)) {
            foreach ($stylesheets as $css) {
                $headLink->appendStylesheet($basePath($css));
            }
        }
    }

    /**
     * @param AdminAwareModuleInterface $module
     */
    protected function setupJavascript(AdminAwareModuleInterface $module)
    {
        $headScript = $this->viewHelperManager->get('headScript');
        $basePath = $this->viewHelperManager->get('basePath');

        $javascripts = $module->getAdminJavascript();
        if (!empty($javascripts) && is_array($javascripts)) {
            foreach ($javascripts as $js) {
                $headScript->appendFile($basePath($js));
            }
        }
    }

    /**
     * @param AdminAwareModuleInterface $module
     */
    protected function setupFormElements(AdminAwareModuleInterface $module)
    {
        $formElementHelper = $this->viewHelperManager->get('formElement');

        $formElements = $module->getAdminFormViewHelpers();
        if (!empty($formElements) && is_array($formElements)) {
            foreach ($formElements as $formClass => $formAlias) {
                if (!is_string($formClass)) {
                    $formClass = $formAlias;

                    $formAlias = 'form' . strtolower(substr($formAlias, strrpos($formAlias, '\\') + 1));
                }

                $formElementHelper->addClass($formClass, $formAlias);
            }
        }
    }
}
