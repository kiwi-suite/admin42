<?php
namespace Admin42\Form\User;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ManageForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->add(new Csrf('csrf'));

        $username = new Text("username");
        $username->setLabel("Username");
        $this->add($username);

        $email = new Email('email');
        $email->setLabel('Email');
        $email->setAttribute("required", "required");
        $this->add($email);

        $password = new Password("password");
        $password->setLabel("Password");
        $this->add($password);

        $passwordRepeat = new Password("passwordRepeat");
        $passwordRepeat->setLabel("Password (repeat)");
        $this->add($passwordRepeat);

        $displayName = new Text('displayName');
        $displayName->setLabel('Display Name');
        $this->add($displayName);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'passwordRepeat' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password'
                        ),
                    ),
                ),
            ),
        );
    }
}
