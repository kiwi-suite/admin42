<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
