<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Service;

use Admin42\View\Helper\Media;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MediaFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Admin
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mediaTableGateway = $serviceLocator->getServiceLocator()->get('TableGateway')->get('Admin42\Media');

        return new Media($mediaTableGateway);
    }
}
