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


namespace Admin42\FormElements\Service;

use Admin42\FormElements\Link;
use Admin42\Link\LinkProvider;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LinkFactory implements FactoryInterface
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
        if ($options === null) {
            $options = [];
        }

        $name = null;
        if (isset($options['name'])) {
            $name = $options['name'];
        }

        if (isset($options['options'])) {
            $options = $options['options'];
        }

        /** @var LinkProvider $linkProvider */
        $linkProvider = $container->get(LinkProvider::class);

        $link = new Link($name, $options);
        $link->setAllLinkTypes($linkProvider->getAvailableAdapters());

        $partialList = [];
        foreach ($linkProvider->getAvailableAdapters() as $adapterName) {
            $partialList[$adapterName] = $linkProvider->getAdapter($adapterName)->getPartials();
        }
        $link->setLinkPartials($partialList);

        return $link;
    }
}
