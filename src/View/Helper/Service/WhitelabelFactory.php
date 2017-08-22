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


namespace Admin42\View\Helper\Service;

use Core42\Model\GenericModel;
use Core42\View\Helper\Proxy;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class WhitelabelFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @throws ServiceNotFoundException if unable to resolve the service
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service
     * @throws ContainerException if any other error occurs
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $config = (isset($config['whitelabel'])) ? $config['whitelabel'] : [];

        $filteredConfig = [];

        /** @var DashToCamelCase $filter */
        $filter = $container->get('FilterManager')->get(DashToCamelCase::class);
        foreach ($config as $name => $value) {
            $filteredConfig[\lcfirst($filter->filter($name))] = $value;
        }

        $genericModel = new GenericModel($filteredConfig);

        return new Proxy(
            $genericModel
        );
    }
}
