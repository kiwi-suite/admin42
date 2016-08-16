<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Authentication\Service;

use Admin42\Authentication\AuthenticationService;
use Admin42\TableGateway\UserTableGateway;
use Core42\Authentication\Storage\Session;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticationServiceFactory implements FactoryInterface
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
        $sessionStorage = new Session(
            'admin42_auth',
            'storage',
            $container->get('Zend\Session\Service\SessionManager')
        );
        $authenticationService = new AuthenticationService($sessionStorage);
        $authenticationService->setTableGateway($container->get('TableGateway')->get(UserTableGateway::class));

        return $authenticationService;
    }
}
