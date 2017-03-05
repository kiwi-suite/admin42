<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Admin42\Session\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Uri\Uri;

class SessionConfigFactory implements FactoryInterface
{
    /**
     * @var Uri
     */
    protected $adminUri;

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @throws ServiceNotFoundException if unable to resolve the service
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service
     * @throws ContainerException if any other error occurs
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($this->getConfig($container));

        return $sessionConfig;
    }

    protected function getConfig(ContainerInterface $container)
    {
        $config = $container->get('config')['session_config'];

        if (!\array_key_exists('cookie_path', $config)) {
            $config['cookie_path'] = $this->getAdminUri($container)->getPath();
        }

        if (!\array_key_exists('cookie_domain', $config)) {
            $config['cookie_domain'] = $this->getAdminUri($container)->getHost();
        }

        if (!\array_key_exists('cookie_secure', $config)) {
            $config['cookie_secure'] = ($this->getAdminUri($container)->getScheme() == 'https');
        }

        return $config;
    }

    protected function getAdminUri(ContainerInterface $container)
    {
        if (!($this->adminUri instanceof Uri)) {
            $router = $container->get('HttpRouter');
            $url = $router->assemble([], ['name' => 'admin', 'force_canonical' => true]);

            $this->adminUri = new Uri($url);
        }

        return $this->adminUri;
    }
}
