<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\User;

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\User;
use Core42\Command\Migration\AbstractCommand;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\EmailAddress;

class LoginCommand extends AbstractCommand
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var string
     */
    private $identity;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $identityType;

    /**
     * @var User
     */
    private $user;

    /**
     * @param string $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setPassword((isset($values['password'])) ? $values['password'] : null);
        $this->setIdentity((isset($values['identity'])) ? $values['identity'] : null);
    }

    /**
     *
     */
    protected function preExecute()
    {
        $this->authenticationService = $this->getServiceManager()->get('Admin42\Authentication');

        if (empty($this->identity)) {
            $this->addError('identity', "Can't be empty");
        }

        if (empty($this->password)) {
            $this->addError("password", "Can't be empty");
        }

        $emailValidator = new EmailAddress();
        $this->identityType = ($emailValidator->isValid($this->identity)) ? 'email' : 'username';

        $userTableGateway = $this->getTableGateway('Admin42\User');

        $resultSet = $userTableGateway->select([
            $this->identityType => $this->identity
        ]);

        $bCrypt = new Bcrypt();

        if ($resultSet->count() != 1) {
            $bCrypt->create("test");

            $this->addError("identity", "Invalid username or password");

            return;
        }

        /** @var User $user */
        $user = $resultSet->current();
        if (!$bCrypt->verify($this->password, $user->getPassword())) {
            $this->addError("identity", "Invalid username or password");

            return;
        }

        if (!in_array($user->getStatus(), [User::STATUS_ACTIVE])) {
            $this->addError("identity", "Invalid username or password");

            return;
        }

        $this->user = $user;
    }

    /**
     *
     */
    protected function execute()
    {
        $authResult = new Result(
            Result::SUCCESS,
            $this->user->getId()
        );

        $this->authenticationService->setAuthResult($authResult);
        $this->authenticationService->authenticate();

        $this->user->setLastLogin(new \DateTime());
        $this->getTableGateway('Admin42\User')->update($this->user);
    }
}
