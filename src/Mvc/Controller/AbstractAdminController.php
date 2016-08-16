<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Mvc\Controller;

use Admin42\Authentication\AuthenticationService;
use Core42\Mvc\Controller\AbstractActionController;

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
}
