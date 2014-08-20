<?php
namespace Admin42\Form\User;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class LostPasswordForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(new Csrf("csrf"));

        $email = new Text("email");
        $email->setLabel("Email");
        $this->add($email);
    }
}
