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

class CreateEditForm extends Form
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
            'name' => 'username',
            'type' => 'text',
            'label' => 'Username',
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Email',
            'required' => true,
        ]);

        $this->add([
            'name' => 'displayName',
            'type' => 'text',
            'label' => 'Display Name',
        ]);

        $this->add([
            'name' => 'role',
            'type' => 'role',
            'label' => 'Role',
            'required' => true,
        ]);


        $this->add([
            'name' => 'shortName',
            'type' => 'text',
            'label' => 'field.short-name',
            'maxLength' => 2,
        ]);
    }
}
