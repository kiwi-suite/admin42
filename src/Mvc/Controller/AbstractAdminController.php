<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\Mvc\Controller;

use Admin42\Authentication\AuthenticationService;
use Core42\Mvc\Controller\AbstractActionController;
use Core42\Permission\PermissionInterface;
use Core42\Permission\Service\PermissionPluginManager;

abstract class AbstractAdminController extends AbstractActionController
{
    /**
     * @return \Admin42\Model\User|null
     */
    protected function getIdentity()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceManager()->get(AuthenticationService::class);

        if ($authenticationService->hasIdentity()) {
            return $authenticationService->getIdentity();
        }
    }

    /**
     * @return bool
     */
    protected function hasIdentity()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceManager()->get(AuthenticationService::class);

        return $authenticationService->hasIdentity();
    }

    /**
     * @return PermissionInterface
     */
    protected function getPermissionService()
    {
        return $this->getServiceManager()->get(PermissionPluginManager::class)->get('admin42');
    }
}
