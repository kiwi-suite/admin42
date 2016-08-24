<?php
namespace Admin42;

use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Config;
use Zend\Stdlib\ArrayUtils;
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

        $serviceManager->get(TranslatorInterface::class)->setLocale('en-US');

        $this->viewHelperManager = $serviceManager->get('ViewHelperManager');

        $formElementConfig = [];
        $viewHelperConfig = [];

        /** @var ModuleManager $moduleManager */
        $moduleManager = $serviceManager->get(ModuleManager::class);

        foreach ($moduleManager->getLoadedModules() as $module) {
            if (!($module instanceof AdminAwareModuleInterface)) {
                continue;
            }

            $this->setupJavascript($module);
            $this->setupStylesheets($module);

            $formElementConfig = ArrayUtils::merge($module->getAdminFormElements(), $formElementConfig);
            $viewHelperConfig = ArrayUtils::merge($module->getAdminViewHelpers(), $viewHelperConfig);
        }

        $smConfig = new Config($viewHelperConfig);
        $smConfig->configureServiceManager($this->viewHelperManager);

        $formElementManager = $serviceManager->get("FormElementManager");
        $smConfig = new Config($formElementConfig);
        $smConfig->configureServiceManager($formElementManager);
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

        $javaScripts = $module->getAdminJavascript();
        if (!empty($javaScripts) && is_array($javaScripts)) {
            foreach ($javaScripts as $js) {
                $headScript->appendFile($basePath($js));
            }
        }
    }
}
