<?php
namespace Admin42\Link\Service;

use Admin42\Link\LinkProvider;
use Admin42\TableGateway\LinkTableGateway;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LinkProviderFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['link'];

        $linkProvider = new LinkProvider(
            $container->get('TableGateway')->get(LinkTableGateway::class),
            $container->get('Cache\Link')
        );

        foreach ($config['adapter'] as $name => $service) {
            $linkProvider->addAdapter($name, $container->get($service));
        }

        return $linkProvider;
    }
}