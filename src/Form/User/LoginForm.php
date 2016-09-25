<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
            'name' => "csrf",
            "type" => "csrf",
        ]);

        $this->add([
            'name' => "identity",
            "type" => "text",
            "label" => "field.usernameoremail",
            "template" => "partial/admin42/form/no-layout/text"
        ]);

        $this->add([
            'name' => "password",
            "type" => "password",
            "label" => "field.password",
            "template" => "partial/admin42/form/no-layout/password"
        ]);
    }
}
