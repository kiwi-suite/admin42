<?php
namespace Admin42\Command\User;

use Admin42\Model\User;
use Core42\Command\AbstractCommand;


class CreateCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var User
     */
    private $model;

    protected function preExecute()
    {
        $this->model = new User();

        $this->model->setUsername($this->username)
                        ->setPassword($this->password)
                        ->setEmail($this->email);

        if (!$this->model->isValid(array('username', 'password', 'email'))) {
            $this->setCommandErrors($this->model->getInputFilter()->getMessages());
            return;
        }
    }

    protected function execute()
    {
        $dateTime = new \DateTime();
        $this->model->setUpdated($dateTime)
                    ->setCreated($dateTime);


        $this->getServiceManager()->get('Admin42\UserTableGateway')->inset($this->model);
    }
}

