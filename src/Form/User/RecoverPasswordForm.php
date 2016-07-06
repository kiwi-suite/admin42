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
use Zend\Form\Element\Password;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class RecoverPasswordForm extends Form implements InputFilterProviderInterface
{
    /**
     *
     */
    public function init()
    {
        $this->add(new Csrf('csrf'));

        $password = new Password("password");
        $password->setLabel("field.password");
        $this->add($password);

        $passwordRepeat = new Password("passwordRepeat");
        $passwordRepeat->setLabel("field.password-repeat");
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
        return [
            'passwordRepeat' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password'
                        ],
                    ],
                ],
            ],
        ];
    }
}
