<?php

namespace Admin42\Model;

use Core42\Model\AbstractModel;

class User extends AbstractModel
{

    public $inputFilterSpecification = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'digits',
                    'options' => null
                    ),
                array(
                    'name' => 'notempty',
                    'options' => null
                    )
                )
            ),
        'username' => array(
            'name' => 'username',
            'required' => true,
            'validators' => array(array(
                    'name' => 'notempty',
                    'options' => null
                    ))
            ),
        'password' => array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'notempty',
                    'options' => null
                    )
                )
            )
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


}

