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

namespace Admin42\ModuleManager;

abstract class ApplicationConfigCleanup
{
    public static function cleanup(array $config)
    {
        $config = self::cleanupViewHelper($config);
        $config = self::cleanupFormElements($config);

        return $config;
    }

    protected static function cleanupViewHelper(array $config)
    {
        foreach ($config['view_helpers']['aliases'] as $name => $value) {
            if (substr(strtolower($name), 0, 4) == 'form') {
                unset($config['view_helpers']['aliases'][$name]);
            }
        }

        foreach ($config['view_helpers']['factories'] as $name => $value) {
            if (substr($name, 0, 9) == 'Zend\\Form') {
                unset($config['view_helpers']['factories'][$name]);
            }
        }

        return $config;
    }

    protected static function cleanupFormElements(array $config)
    {
        foreach ($config['form_elements']['aliases'] as $name => $value) {
            if (strpos($value, 'Zend\\Form\\') !== false) {
                unset($config['form_elements']['aliases'][$name]);
            }
        }

        foreach ($config['form_elements']['factories'] as $name => $value) {
            if (strpos($name, 'Zend\\Form\\') !== false) {
                unset($config['form_elements']['factories'][$name]);
            }
        }

        return $config;
    }
}
