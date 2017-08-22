<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\Authentication;

use Admin42\Model\User;
use Admin42\TableGateway\UserTableGateway;
use Zend\Authentication\Result;

class AuthenticationService extends \Core42\Authentication\AuthenticationService
{
    /**
     * @var User
     */
    protected $identity;

    /**
     * @var UserTableGateway
     */
    protected $tableGateway;

    /**
     * @param UserTableGateway $tableGateway
     */
    public function setTableGateway(UserTableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Authenticates and provides an authentication result
     *
     * @return Result
     */
    public function authenticate()
    {
        $result = parent::authenticate();

        if (!$result->isValid()) {
            return $result;
        }

        $this->getIdentity();
    }

    /**
     * Returns the authenticated identity or null if no identity is available
     *
     * @return User|null
     */
    public function getIdentity()
    {
        if ($this->identity instanceof User) {
            return $this->identity;
        }

        $identity = parent::getIdentity();

        if (empty($identity)) {
            $this->clearIdentity();

            return;
        }

        $identity = $this->tableGateway->selectByPrimary($identity);
        if (!($identity instanceof User)) {
            $this->clearIdentity();

            return;
        }

        if (!\in_array($identity->getStatus(), [User::STATUS_ACTIVE])) {
            $this->clearIdentity();

            return;
        }

        $this->identity = $identity;

        return $identity;
    }

    /**
     * Clears the identity
     *
     * @return void
     */
    public function clearIdentity()
    {
        parent::clearIdentity();
        $this->identity = null;
    }

    /**
     * Returns true if and only if an identity is available
     *
     * @return bool
     */
    public function hasIdentity()
    {
        $storageCheck = !$this->getStorage()->isEmpty();
        if (!$storageCheck) {
            return false;
        }

        return $this->getIdentity() instanceof User;
    }
}
