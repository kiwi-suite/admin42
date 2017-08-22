<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\ModuleManager;

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

trait GetAdminConfigTrait
{
    public function getAdminConfig()
    {
        $config = [];
        $configPath = \dirname((new \ReflectionClass($this))->getFileName()) . '/../config/admin/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
