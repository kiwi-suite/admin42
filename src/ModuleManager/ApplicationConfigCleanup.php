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
            if (\mb_substr(\mb_strtolower($name), 0, 4) == 'form') {
                unset($config['view_helpers']['aliases'][$name]);
            }
        }

        foreach ($config['view_helpers']['factories'] as $name => $value) {
            if (\mb_substr($name, 0, 9) == 'Zend\\Form') {
                unset($config['view_helpers']['factories'][$name]);
            }
        }

        return $config;
    }

    protected static function cleanupFormElements(array $config)
    {
        foreach ($config['form_elements']['aliases'] as $name => $value) {
            if (\mb_strpos($value, 'Zend\\Form\\') !== false) {
                unset($config['form_elements']['aliases'][$name]);
            }
        }

        foreach ($config['form_elements']['factories'] as $name => $value) {
            if (\mb_strpos($name, 'Zend\\Form\\') !== false) {
                unset($config['form_elements']['factories'][$name]);
            }
        }

        return $config;
    }
}
