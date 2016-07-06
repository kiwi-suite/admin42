<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Service;

use Admin42\View\Helper\Admin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Admin
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('Config');
        $userTableGateway = $serviceLocator->getServiceLocator()->get('TableGateway')->get('Admin42\User');
        $mediaOptions = $serviceLocator->getServiceLocator()->get('Admin42\MediaOptions');

        return new Admin($config['admin'], $userTableGateway, $mediaOptions, $config['media_url']);
    }
}
