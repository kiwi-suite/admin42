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

namespace Admin42\Command\User;

use Admin42\Authentication\AuthenticationService;
use Core42\Command\AbstractCommand;

class LogoutCommand extends AbstractCommand
{
    /**
     *
     */
    protected function execute()
    {
        $this->getServiceManager()->get(AuthenticationService::class)->clearIdentity();
    }
}
