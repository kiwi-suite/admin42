<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Form\User;

use Admin42\FormElements\Role;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class CreateEditForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $this->add(new Csrf('csrf'));

        $username = new Text("username");
        $username->setLabel("Username");
        $this->add($username);

        $email = new Email('email');
        $email->setLabel('Email');
        $email->setAttribute("required", "required");
        $this->add($email);

        $displayName = new Text('displayName');
        $displayName->setLabel('Display Name');
        $this->add($displayName);

        /** @var Role $role */
        $role = $this->getFormFactory()->getFormElementManager()->get('role');
        $role->setName("role");
        $role->setLabel("Role");
        $role->setAttribute("required", "required");
        $this->add($role);
    }
}
