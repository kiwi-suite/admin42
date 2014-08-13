<?php
namespace Admin42\Permission\Rbac\Service;

use Admin42\Permission\Rbac\Identity\IdentityRoleProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IdentityRoleProviderFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityRoleProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new IdentityRoleProvider($serviceLocator->get('Admin42\Authentication'));
    }
}
