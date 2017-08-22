<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\Form\User;

use Admin42\FormElements\Form;

class LoginForm extends Form
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
            'name' => 'identity',
            'type' => 'text',
            'label' => 'field.usernameoremail',
            'template' => 'partial/admin42/form/no-layout/text',
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'label' => 'field.password',
            'template' => 'partial/admin42/form/no-layout/password',
        ]);
    }
}
