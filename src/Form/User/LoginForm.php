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
            "options" => [
                "label" => "field.usernameoremail"
            ]
        ]);

        $this->add([
            'name' => "password",
            "type" => "password",
            "options" => [
                "label" => "field.password"
            ]
        ]);
    }
}
