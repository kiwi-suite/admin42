<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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

            return null;
        }

        $identity = $this->tableGateway->selectByPrimary($identity);
        if (!($identity instanceof User)) {
            $this->clearIdentity();

            return null;
        }

        if (!in_array($identity->getStatus(), [User::STATUS_ACTIVE])) {
            $this->clearIdentity();

            return null;
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

        return ($this->getIdentity() instanceof User);
    }
}
