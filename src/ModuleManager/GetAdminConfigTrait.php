<?php
namespace Admin42\ModuleManager;

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

trait GetAdminConfigTrait
{
    public function getAdminConfig()
    {
        $config = [];
        $configPath = dirname((new \ReflectionClass($this))->getFileName()) . '/../config/admin/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
