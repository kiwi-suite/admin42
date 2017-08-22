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


namespace Admin42\Crud\Service;

use Admin42\Crud\AbstractOptions;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class CrudOptionsPluginManager extends AbstractPluginManager
{
    /**
     * @var string
     */
    protected $instanceOf = AbstractOptions::class;

    /**
     * Should the services be shared by default?
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * CrudOptionsPluginManager constructor.
     * @param \Interop\Container\ContainerInterface|null|ConfigInterface $configInstanceOrParentLocator
     * @param array $config
     */
    public function __construct($configInstanceOrParentLocator, array $config)
    {
        $this->addAbstractFactory(new CrudOptionsFallbackAbstractFactory());

        parent::__construct($configInstanceOrParentLocator, $config);
    }
}
