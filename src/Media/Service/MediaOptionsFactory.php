<?php
namespace Admin42\Media\Service;

use Admin42\Media\MediaOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MediaOptionsFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mediaConfig = $serviceLocator->get('config')['media'];

        return new MediaOptions($mediaConfig);
    }
}
