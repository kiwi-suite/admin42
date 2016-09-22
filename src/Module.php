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
use Admin42\FormElements\Checkbox;
use Admin42\FormElements\Date;
use Admin42\FormElements\DateTime;
use Admin42\FormElements\Email;
use Admin42\FormElements\Factory\ElementFactory;
use Admin42\FormElements\Fieldset;
use Admin42\FormElements\Hidden;
use Admin42\FormElements\MultiCheckbox;
use Admin42\FormElements\Password;
use Admin42\FormElements\Radio;
use Admin42\FormElements\Select;
use Admin42\FormElements\Service\CountryFactory;
use Admin42\FormElements\Service\LinkFactory;
use Admin42\FormElements\Service\RoleFactory;
use Admin42\FormElements\Stack;
use Admin42\FormElements\Tags;
use Admin42\FormElements\Text;
use Admin42\FormElements\Textarea;
use Admin42\FormElements\Wysiwyg;
use Admin42\FormElements\YouTube;
use Admin42\ModuleManager\ApplicationConfigCleanup;
use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Admin42\ModuleManager\GetAdminConfigTrait;
use Core42\Console\Console;
use Core42\ModuleManager\Feature\CliConfigProviderInterface;
use Core42\ModuleManager\GetConfigTrait;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Stdlib\ArrayUtils;

class Module implements
    ConfigProviderInterface,
    InitProviderInterface,
    DependencyIndicatorInterface,
    CliConfigProviderInterface,
    AdminAwareModuleInterface
{
    /**
     *
     */
    const ENVIRONMENT_ADMIN = 'admin';

    use GetConfigTrait;
    use GetAdminConfigTrait;

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $serviceManager = $manager->getEvent()->getParam('ServiceManager');
        if ($serviceManager->get(Environment::class)->is(self::ENVIRONMENT_ADMIN)) {
            $manager->getEventManager()->attach(
                ModuleEvent::EVENT_LOAD_MODULES_POST,
                [$this, 'setupAdminConfig'],
                PHP_INT_MAX
            );
        }
    }

    /**
     * @param ModuleEvent $e
     */
    public function setupAdminConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);
        $config = ApplicationConfigCleanup::cleanup($config);

        $adminConfig = [];

        foreach ($e->getTarget()->getLoadedModules() as $module) {
            if (!($module instanceof AdminAwareModuleInterface)) {
                continue;
            }

            $moduleConfig = $module->getAdminConfig();
            if (!is_array($moduleConfig)) {
                continue;
            }

            $adminConfig = ArrayUtils::merge($adminConfig, $moduleConfig);
        }

        if (!empty($adminConfig)) {
            $config = ArrayUtils::merge($config, $adminConfig);
        }

        $configListener->setMergedConfig($config);
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
     * @return mixed
     */
    public function getCliConfig()
    {
        return include_once __DIR__ . '/../config/cli/cli.config.php';
    }
}
