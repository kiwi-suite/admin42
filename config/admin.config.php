<?php
namespace Admin42;

use Admin42\FormElements\Date;
use Admin42\FormElements\DateTime;
use Admin42\FormElements\GoogleMap;
use Admin42\FormElements\Service\CountryFactory;
use Admin42\FormElements\Service\DynamicFactory;
use Admin42\FormElements\Service\LinkFactory;
use Admin42\FormElements\Service\RoleFactory;
use Admin42\FormElements\Tags;
use Admin42\FormElements\Wysiwyg;
use Admin42\FormElements\YouTube;

use Admin42\View\Helper\Admin;
use Admin42\View\Helper\Form\Form;
use Admin42\View\Helper\Form\FormCollection;
use Admin42\View\Helper\Form\FormElement;
use Admin42\View\Helper\Form\FormRow;
use Admin42\View\Helper\Form\FormWysiwyg;
use Admin42\View\Helper\Service\AdminFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'admin' => [
        'job_auth' => '',
        'angular'  => [
            'modules' => ['admin42']
        ],

        'view_helpers' => [
            'factories'  => [
                Admin::class            => AdminFactory::class,

                Form::class             => InvokableFactory::class,
                FormCollection::class   => InvokableFactory::class,
                FormElement::class      => InvokableFactory::class,
                FormRow::class          => InvokableFactory::class,
                FormWysiwyg::class      => InvokableFactory::class,
            ],
            'aliases' => [
                'admin'                 => Admin::class,
                'form'                  => Form::class,
                'formcollection'        => FormCollection::class,
                'form_collection'        => FormCollection::class,
                'formCollection'        => FormCollection::class,
                'formelement'           => FormElement::class,
                'formElement'           => FormElement::class,
                'form_element'           => FormElement::class,
                'formrow'               => FormRow::class,
                'form_row'               => FormRow::class,
                'formRow'               => FormRow::class,
                'formwysiwyg'           => FormWysiwyg::class,
                'formWysiwyg'           => FormWysiwyg::class,
                'form_wysiwyg'           => FormWysiwyg::class,
            ],
        ],

        'form_elements' => [
            'factories' => [
                'role'              => RoleFactory::class,
                'dynamic'           => DynamicFactory::class,
                'link'              => LinkFactory::class,
                'country'           => CountryFactory::class,

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

        'display-timezone' => 'Europe/Vienna',

        'whitelabel' => [
            'show-topbar-title'         => true,
            'topbar-title'              => 'kiw<span class="text-r42">i</span>42',
            'logo-icon'                 => 'assets/admin/core/images/logo-icon.png', // topbar -> light
            'logo-lg'                   => 'assets/admin/core/images/logo-lg.png', // login top -> dark
            'logo-xs'                   => 'assets/admin/core/images/logo-icon.png', // sidebar bottom -> light
            'logo-xs-dark'              => 'assets/admin/core/images/logo-lg.png', // login footer -> dark
            'sidebar-bottom-text'       => 'kiw<span class="text-r42">i</span>42&nbsp;&copy;&nbsp;raum42 OG',
            'sidebar-bottom-link'       => 'https://www.raum42.at',
            'sidebar-bottom-link-title' => 'raum42 OG',
        ],

        'google_map' => [
            'api_key' => 'AIzaSyBbVr_HG3DZB2PizS3ZRrX95HYEWfS3m6c',
        ],
    ],
];
