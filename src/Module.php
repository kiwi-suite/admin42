<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42;

use Admin42\ModuleManager\ApplicationConfigCleanup;
use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Admin42\ModuleManager\GetAdminConfigTrait;
use Core42\ModuleManager\Feature\CliConfigProviderInterface;
use Core42\ModuleManager\GetConfigTrait;
use Core42\Mvc\Environment\Environment;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

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
        $config = $configListener->getMergedConfig(false);
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
        $config = [];
        $configPath = dirname((new \ReflectionClass($this))->getFileName()) . '/../config/cli/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
