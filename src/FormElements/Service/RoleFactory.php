<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements\Service;

use Admin42\FormElements\Role;
use Core42\Permission\Rbac\AuthorizationService;
use Core42\Permission\Rbac\Role\RoleInterface;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RoleFactory implements FactoryInterface
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
        /** @var AuthorizationService $permService */
        $permService = $container->get('Core42\Permission')->getService('admin42');
        $permRoles = $permService->getAllRoles();
        $roles = [];
        foreach ($permRoles as $role) {
            if (is_string($role) && $role != $permService->getGuestRole()) {
                $roles[$role] = $role;
            } elseif ($role instanceof RoleInterface && $role->getName() != $permService->getGuestRole()) {
                $roles[$role->getName()] = $role->getName();
            }
        }

        $element = new Role();
        $element->setValueOptions($roles);

        return $element;
    }
}
