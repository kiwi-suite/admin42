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

namespace Admin42\Session\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\Container;
use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Session\SessionManager;
use Zend\Session\Storage\StorageInterface;

class SessionManagerFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = null;
        $storage = null;
        $saveHandler = null;
        $validators = [];
        $options = [];

        $config = $container->get('Admin42\SessionConfig');

        if ($container->has(StorageInterface::class)) {
            $storage = $container->get(StorageInterface::class);
            if (!$storage instanceof StorageInterface) {
                throw new ServiceNotCreatedException(sprintf(
                    'SessionManager requires that the %s service implement %s; received "%s"',
                    StorageInterface::class,
                    StorageInterface::class,
                    (is_object($storage) ? get_class($storage) : gettype($storage))
                ));
            }
        }

        if ($container->has(SaveHandlerInterface::class)) {
            $saveHandler = $container->get(SaveHandlerInterface::class);
            if (!$saveHandler instanceof SaveHandlerInterface) {
                throw new ServiceNotCreatedException(sprintf(
                    'SessionManager requires that the %s service implement %s; received "%s"',
                    SaveHandlerInterface::class,
                    SaveHandlerInterface::class,
                    (is_object($saveHandler) ? get_class($saveHandler) : gettype($saveHandler))
                ));
            }
        }

        $manager = new SessionManager($config, $storage, $saveHandler, $validators, $options);
        Container::setDefaultManager($manager);

        return $manager;
    }
}
