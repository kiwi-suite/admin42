<?php
namespace Admin42\Command\User;

use Core42\Command\AbstractCommand;
use Zend\Authentication\AuthenticationServiceInterface;

class LoginCommand extends AbstractCommand
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
     * @var AuthenticationServiceInterface
     */
    private $authService;

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    protected function extractForm()
    {
        $data = $this->getForm()->getData();
        $this->setUsername($data['username'])
                ->setPassword($data['password']);
    }

    protected function preExecute()
    {
        if ($this->hasErrors()) {
            return ;
        }

        $this->authService = $this->getServiceManager()->get('Core42\Authentication');

        $this->authService->getAdapter()
            ->setIdentity($this->username)
            ->setCredential($this->password);

        $authResult = $this->authService->authenticate();
        if (!$authResult->isValid()) {
            $this->addErrors(array("username" => $authResult->getMessages()));
            return;
        }
    }

    protected function execute()
    {
        //TODO LoginLog and last_login DateTime
    }
}
