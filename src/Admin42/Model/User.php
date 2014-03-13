<?php

namespace Admin42\Model;

use Core42\Model\AbstractModel;

class User extends AbstractModel
{

    public $inputFilterSpecifications = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'digits',
                ),
                array(
                    'name' => 'notempty',
                ),
            ),
        ),
        'username' => array(
            'name' => 'username',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'notempty',
                ),
            ),
        ),
        'password' => array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'notempty',
                ),
            ),
        ),
        'email' => array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'notempty',
                ),
            ),
        ),
        'updated' => array(
            'name' => 'updated',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'notempty',
                ),
            ),
        ),
        'created' => array(
            'name' => 'created',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'notempty',
                ),
            ),
        ),
    );

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


}

