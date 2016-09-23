<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
            'name' => "csrf",
            "type" => "csrf",
        ]);

        $this->add([
            'name' => "username",
            "type" => "text",
            "label" => "Username",
        ]);

        $this->add([
            'name' => "email",
            "type" => "email",
            "label" => "Email",
            "required" => true
        ]);

        $this->add([
            'name' => "displayName",
            "type" => "text",
            "label" => "Display Name"
        ]);

        $this->add([
            'name' => "role",
            "type" => "role",
            "label" => "Role",
            "required" => true,
        ]);


        $this->add([
            'name' => "shortName",
            "type" => "text",
            "label" => "field.short-name",
            "maxLength" => 2
        ]);
    }
}
