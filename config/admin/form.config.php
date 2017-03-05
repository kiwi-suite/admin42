<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42;

use Admin42\FormElements\Checkbox;
use Admin42\FormElements\Csrf;
use Admin42\FormElements\Date;
use Admin42\FormElements\DateTime;
use Admin42\FormElements\Email;
use Admin42\FormElements\Fieldset;
use Admin42\FormElements\Form;
use Admin42\FormElements\Hidden;
use Admin42\FormElements\Link;
use Admin42\FormElements\MultiCheckbox;
use Admin42\FormElements\Password;
use Admin42\FormElements\Radio;
use Admin42\FormElements\Select;
use Admin42\FormElements\Service\ActiveFactory;
use Admin42\FormElements\Service\AdminLanguageFactory;
use Admin42\FormElements\Service\CountryFactory;
use Admin42\FormElements\Service\LinkFactory;
use Admin42\FormElements\Service\OnlineFactory;
use Admin42\FormElements\Service\RoleFactory;
use Admin42\FormElements\Stack;
use Admin42\FormElements\Switcher;
use Admin42\FormElements\Tags;
use Admin42\FormElements\Text;
use Admin42\FormElements\Textarea;
use Admin42\FormElements\Wysiwyg;
use Admin42\FormElements\Youtube;
use Core42\Form\Service\ElementFactory;

return [
    'form_elements' => [
        'factories' => [
            'role'                      => RoleFactory::class,
            'country'                   => CountryFactory::class,
            'onlineSwitcher'            => OnlineFactory::class,
            'activeSwitcher'            => ActiveFactory::class,
            'adminLanguage'             => AdminLanguageFactory::class,

            DateTime::class             => ElementFactory::class,
            Date::class                 => ElementFactory::class,
            Tags::class                 => ElementFactory::class,
            Wysiwyg::class              => ElementFactory::class,
            Youtube::class              => ElementFactory::class,
            Form::class                 => ElementFactory::class,
            Fieldset::class             => ElementFactory::class,
            Stack::class                => ElementFactory::class,
            Checkbox::class             => ElementFactory::class,
            MultiCheckbox::class        => ElementFactory::class,
            Radio::class                => ElementFactory::class,
            Text::class                 => ElementFactory::class,
            Textarea::class             => ElementFactory::class,
            Hidden::class               => ElementFactory::class,
            Password::class             => ElementFactory::class,
            Select::class               => ElementFactory::class,
            Email::class                => ElementFactory::class,
            Csrf::class                 => ElementFactory::class,
            Switcher::class             => ElementFactory::class,
            Link::class                 => LinkFactory::class,
        ],
        'aliases' => [
            'dateTime'                  => DateTime::class,
            'date'                      => Date::class,
            'tags'                      => Tags::class,
            'wysiwyg'                   => Wysiwyg::class,
            'youtube'                   => Youtube::class,
            'stack'                     => Stack::class,
            'checkbox'                  => Checkbox::class,
            'multiCheckbox'             => MultiCheckbox::class,
            'radio'                     => Radio::class,
            'text'                      => Text::class,
            'textarea'                  => Textarea::class,
            'hidden'                    => Hidden::class,
            'password'                  => Password::class,
            'select'                    => Select::class,
            'email'                     => Email::class,
            'csrf'                      => Csrf::class,
            'switcher'                  => Switcher::class,
            'link'                      => Link::class,
            'fieldset'                  => Fieldset::class,
        ],
    ],
];
