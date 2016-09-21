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
            "options" => [
                "label" => "Username"
            ]
        ]);

        $this->add([
            'name' => "email",
            "type" => "email",
            "options" => [
                "label" => "Username"
            ],
            "attribute" => [
                "required" => true
            ]
        ]);

        $this->add([
            'name' => "displayName",
            "type" => "text",
            "options" => [
                "label" => "Display Name"
            ]
        ]);

        $this->add([
            'name' => "role",
            "type" => "role",
            "options" => [
                "label" => "Role"
            ],
            "attribute" => [
                "required" => true
            ]
        ]);


        $this->add([
            'name' => "shortName",
            "type" => "text",
            "options" => [
                "label" => "field.short-name"
            ],
            "attribute" => [
                "maxlength" => 2
            ]
        ]);
    }
}
