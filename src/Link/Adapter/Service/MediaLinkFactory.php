<?php

namespace Admin42\Link\Adapter\Service;

use Admin42\Link\Adapter\MediaLink;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MediaLinkFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MediaLink(
            $serviceLocator->get('TableGateway')->get('Admin42\Media'),
            $serviceLocator->get('config')['media_url']
        );
    }
}
