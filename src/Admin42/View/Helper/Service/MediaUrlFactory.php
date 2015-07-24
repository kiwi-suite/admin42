<?php
namespace Admin42\View\Helper\Service;


use Admin42\View\Helper\MediaUrl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MediaUrlFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MediaUrl(
            $serviceLocator->getServiceLocator()->get('TableGateway')->get('Admin42\Media'),
            $serviceLocator->getServiceLocator()->get('Admin42\MediaOptions'),
            $serviceLocator->getServiceLocator()->get('config')['media_url']
        );
    }
}