<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Admin42\Form\User;

use Admin42\FormElements\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class RecoverPasswordForm extends Form implements InputFilterProviderInterface
{
    /**
     *
     */
    public function init()
    {
        $this->add([
            'name' => 'csrf',
            'type' => 'csrf',
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'label' => 'field.password',
            'template' => 'partial/admin42/form/no-layout/password',
        ]);

        $this->add([
            'name' => 'passwordRepeat',
            'type' => 'password',
            'label' => 'field.password-repeat',
            'template' => 'partial/admin42/form/no-layout/password',
        ]);
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
                            'token' => 'password',
                        ],
                    ],
                ],
            ],
        ];
    }
}
