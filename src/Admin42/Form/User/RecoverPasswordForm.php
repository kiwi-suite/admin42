<?php
namespace Admin42\Form\User;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class RecoverPasswordForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add(new Csrf('csrf'));

        $password = new Password("password");
        $password->setLabel("Password");
        $this->add($password);

        $passwordRepeat = new Password("passwordRepeat");
        $passwordRepeat->setLabel("Password (repeat)");
        $this->add($passwordRepeat);
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
                'required' => true,
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
