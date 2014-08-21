<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Form\User;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ManageForm extends Form implements InputFilterProviderInterface
{
    /**
     *
     */
    public function init()
    {
        $this->add(new Csrf('csrf'));

        $username = new Text("username");
        $username->setLabel("field.username");
        $this->add($username);

        $email = new Email('email');
        $email->setLabel('field.email');
        $email->setAttribute("required", "required");
        $this->add($email);

        $password = new Password("password");
        $password->setLabel("field.password");
        $this->add($password);

        $passwordRepeat = new Password("passwordRepeat");
        $passwordRepeat->setLabel("field.password-repeat");
        $this->add($passwordRepeat);

        $displayName = new Text('displayName');
        $displayName->setLabel('field.display-name');
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
