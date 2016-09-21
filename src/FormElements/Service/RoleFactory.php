<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements\Service;

use Admin42\FormElements\Select;
use Core42\Permission\Service\PermissionPluginManager;
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
        $guestRole = $container->get(PermissionPluginManager::class)->get('admin42')->getGuestRole();
        $permRoles = $container->get(PermissionPluginManager::class)->get('admin42')->getRoles();
        $roles = [];
        foreach ($permRoles as $role) {
            if ($role == $guestRole) {
                continue;
            }
            $role = $container->get(PermissionPluginManager::class)->get('admin42')->getRole($role);

            $options = $role->getOptions();
            if (array_key_exists('assignable', $options) && $options['assignable'] == false) {
                continue;
            }

            $name = (!empty($options['label'])) ? $options['label'] : ucfirst($role->getName());

            $roles[$role->getName()] = $name;
        }
        $element = $container->get('FormElementManager')->get(Select::class);
        $element->setValueOptions($roles);

        return $element;
    }
}
