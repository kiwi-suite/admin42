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
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoleFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var AuthorizationService $permService */
        $permService = $serviceLocator->getServiceLocator()->get('Core42\Permission')->getService('admin42');
        $permRoles = $permService->getAllRoles();
        $roles = array();
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
