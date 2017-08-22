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
            'label' => 'field.username',
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'email',
            'label' => 'field.email',
            'required' => true,
        ]);

        $this->add([
            'name' => 'displayName',
            'type' => 'text',
            'label' => 'field.display-name',
        ]);

        $this->add([
            'name' => 'role',
            'type' => 'role',
            'label' => 'field.role',
            'required' => true,
        ]);


        $this->add([
            'name' => 'shortName',
            'type' => 'text',
            'label' => 'field.short-name',
            'maxLength' => 2,
        ]);

        $this->add([
            'name' => 'locale',
            'type' => 'adminLanguage',
            'label' => 'field.language',
            'required' => true,
        ]);
    }
}
