<?php
namespace Admin42\Form\User;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(new Csrf("csrf"));

        $username = new Text("identity");
        $username->setLabel("Email or Username");
        $this->add($username);

        $password = new Password("password");
        $password->setLabel("Password");
        $this->add($password);
    }
}
