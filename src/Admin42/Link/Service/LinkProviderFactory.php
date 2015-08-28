<?php
namespace Admin42\Link\Service;

use Admin42\Link\LinkProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkProviderFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config')['link'];

        $linkProvider = new LinkProvider(
            $serviceLocator->get('TableGateway')->get('Admin42\Link'),
            $serviceLocator->get('Cache\Link')
        );

        foreach ($config['adapter'] as $name => $service) {
            $linkProvider->addAdapter($name, $serviceLocator->get($service));
        }

        return $linkProvider;
    }
}
