<?php

namespace Admin42\Model;

use Core42\Model\AbstractModel;

class User extends AbstractModel
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @param int $id
     * @return \Admin42\Model\User
     */
    public function setId($id)
    {
        $this->set('id', $id);
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @param string $username
     * @return \Admin42\Model\User
     */
    public function setUsername($username)
    {
        $this->set('username', $username);
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->get('username');
    }

    /**
     * @param string $password
     * @return \Admin42\Model\User
     */
    public function setPassword($password)
    {
        $this->set('password', $password);
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->get('password');
    }

    /**
     * @param string $email
     * @return \Admin42\Model\User
     */
    public function setEmail($email)
    {
        $this->set('email', $email);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->get('email');
    }

    /**
     * @return string|null
     */
    public function getDisplayName()
    {
        return $this->get('displayName');
    }

    /**
     * @param string|null $displayName
     * @return \Admin42\Model\User
     */
    public function setDisplayName($displayName)
    {
        return $this->set('displayName', $displayName);
    }

    /**
     * @return string|null
     */
    public function getHash()
    {
        return $this->get('hash');
    }

    /**
     * @param string $hash
     * @return \Admin42\Model\User
     */
    public function setHash($hash)
    {
        return $this->set('hash', $hash);
    }

    /**
     * @param string $status
     * @return \Admin42\Model\User
     */
    public function setStatus($status)
    {
        return $this->set('status', $status);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->get('status');
    }

    /**
     * @param \DateTime $updated
     * @return \Admin42\Model\User
     */
    public function setUpdated($updated)
    {
        $this->set('updated', $updated);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->get('updated');
    }

    /**
     * @param \DateTime $created
     * @return \Admin42\Model\User
     */
    public function setCreated($created)
    {
        $this->set('created', $created);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->get('created');
    }

    /**
     * @param \DateTime|null $lastLogin
     * @return AbstractModel
     */
    public function setLastLogin($lastLogin)
    {
        return $this->set('lastLogin', $lastLogin);
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->get('lastLogin');
    }
}

