<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
