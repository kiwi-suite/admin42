<?php
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
