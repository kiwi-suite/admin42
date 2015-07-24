<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Service;

use Admin42\View\Helper\Link;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Link
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $linkTableGateway = $serviceLocator->getServiceLocator()->get('TableGateway')->get('Admin42\Link');
        $linkProvider = $serviceLocator->getServiceLocator()->get('Admin42\LinkProvider');

        return new Link($linkTableGateway, $linkProvider);
    }
}
