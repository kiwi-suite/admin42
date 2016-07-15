<?php
namespace Admin42\Navigation\Badge\Service;

use Admin42\Navigation\Badge\BadgeContainer;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BadgeContainerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return BadgeContainer
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $config = (array_key_exists('forms', $config)) ? $config['forms'] : [];

        $badgeContainer = new BadgeContainer();

        return $badgeContainer;
    }
}