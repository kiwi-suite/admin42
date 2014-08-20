<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\User;

use Core42\Command\AbstractCommand;

class LogoutCommand extends AbstractCommand
{

    /**
     *
     */
    protected function execute()
    {
        $this->getServiceManager()->get('Admin42\Authentication')->clearIdentity();
    }
}
