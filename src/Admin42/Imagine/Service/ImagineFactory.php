<?php
namespace Admin42\Imagine\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImagineFactory implements FactoryInterface
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
        $imagineAdapter = ucfirst($mediaConfig['images']['adapter']);

        $class = 'Imagine\\' . $imagineAdapter .'\\Imagine';

        return new $class;
    }
}
