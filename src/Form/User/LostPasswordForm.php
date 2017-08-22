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

class LostPasswordForm extends Form
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
            'name' => 'email',
            'type' => 'email',
            'label' => 'field.email',
            'template' => 'partial/admin42/form/no-layout/email',
        ]);
    }
}
