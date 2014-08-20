<?php
namespace Admin42\Navigation\Listener;

use Core42\Navigation\Listener\RbacListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RbacListenerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RbacListener($serviceLocator->get('Core42\Permission')->getService('admin42'));
    }
}
