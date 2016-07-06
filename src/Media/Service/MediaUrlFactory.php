<?php
namespace Admin42\Media\Service;

use Admin42\Media\MediaUrl;
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
            $serviceLocator->get('TableGateway')->get('Admin42\Media'),
            $serviceLocator->get('Admin42\MediaOptions'),
            $serviceLocator->get('config')['media_url'],
            $serviceLocator->get('Cache\Media')
        );
    }
}
