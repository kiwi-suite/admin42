<?php
namespace Admin42;

use Admin42\FormElements\Date;
use Admin42\FormElements\DateTime;
use Admin42\FormElements\FileSelect;
use Admin42\FormElements\GoogleMap;
use Admin42\FormElements\Service\CountryFactory;
use Admin42\FormElements\Service\DynamicFactory;
use Admin42\FormElements\Service\LinkFactory;
use Admin42\FormElements\Service\RoleFactory;
use Admin42\FormElements\Tags;
use Admin42\FormElements\Wysiwyg;
use Admin42\FormElements\YouTube;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'form_elements' => [
        'factories' => [
            'role'              => RoleFactory::class,
            'dynamic'           => DynamicFactory::class,
            'link'              => LinkFactory::class,
            'country'           => CountryFactory::class,

            FileSelect::class   => InvokableFactory::class,
            DateTime::class     => InvokableFactory::class,
            Date::class         => InvokableFactory::class,
            Tags::class         => InvokableFactory::class,
            Wysiwyg::class      => InvokableFactory::class,
            YouTube::class      => InvokableFactory::class,
            GoogleMap::class    => InvokableFactory::class,
        ],
        'aliases' => [
            'fileSelect' => FileSelect::class,
            'datetime'   => DateTime::class ,
            'date'       => Date::class,
            'tags'       => Tags::class,
            'wysiwyg'    => Wysiwyg::class,
            'youtube'    => YouTube::class,
            'googlemap'  => GoogleMap::class,
        ],
    ],
];
