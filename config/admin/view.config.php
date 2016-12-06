<?php
namespace Admin42;


use Admin42\View\Helper\Admin;
use Admin42\View\Helper\Angular;
use Admin42\View\Helper\Form\Form;
use Admin42\View\Helper\Form\FormCheckbox;
use Admin42\View\Helper\Form\FormDate;
use Admin42\View\Helper\Form\FormDateTime;
use Admin42\View\Helper\Form\FormElement;
use Admin42\View\Helper\Form\FormFieldset;
use Admin42\View\Helper\Form\FormHelper;
use Admin42\View\Helper\Form\FormHidden;
use Admin42\View\Helper\Form\FormLink;
use Admin42\View\Helper\Form\FormMultiCheckbox;
use Admin42\View\Helper\Form\FormRadio;
use Admin42\View\Helper\Form\FormRow;
use Admin42\View\Helper\Form\FormSelect;
use Admin42\View\Helper\Form\FormStack;
use Admin42\View\Helper\Form\FormText;
use Admin42\View\Helper\Form\FormTextarea;
use Admin42\View\Helper\Form\FormWysiwyg;
use Admin42\View\Helper\PageHeader;
use Admin42\View\Helper\Service\AdminFactory;
use Admin42\View\Helper\Service\AngularFactory;
use Admin42\View\Helper\Service\UserFactory;
use Admin42\View\Helper\Service\WhitelabelFactory;
use Admin42\View\Helper\User;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_manager' => [
        'display_not_found_reason'  => false,
        'display_exceptions'        => false,
        'not_found_template'        => 'admin/error/404',
        'layout'                    => 'admin/layout/layout',
        'template_map'              => [
            'admin/layout/layout'       => __DIR__ . '/../../view/layout/layout.phtml',
            'admin/layout/layout-min'   => __DIR__ . '/../../view/layout/layout-min.phtml',
            'admin/layout/dialog'       => __DIR__ . '/../../view/layout/dialog.phtml',
            'admin/error/404'           => __DIR__ . '/../../view/error/404.phtml',
        ],
        'template_path_stack'       => [
            __NAMESPACE__               => __DIR__ . '/../../view',
        ],
        'strategies'                => [
            'ViewJsonStrategy',
        ],
    ],

    'view_helpers' => [
        'factories'  => [
            'whitelabel'            => WhitelabelFactory::class,
            PageHeader::class       => InvokableFactory::class,
            Admin::class            => AdminFactory::class,
            Angular::class          => AngularFactory::class,
            User::class             => UserFactory::class,

            Form::class             => InvokableFactory::class,
            FormHelper::class       => InvokableFactory::class,
            FormFieldset::class     => InvokableFactory::class,
            FormElement::class      => InvokableFactory::class,
            FormRow::class          => InvokableFactory::class,
            FormWysiwyg::class      => InvokableFactory::class,
            FormSelect::class       => InvokableFactory::class,
            FormRadio::class        => InvokableFactory::class,
            FormDateTime::class     => InvokableFactory::class,
            FormDate::class         => InvokableFactory::class,
            FormCheckbox::class     => InvokableFactory::class,
            FormStack::class        => InvokableFactory::class,
            FormMultiCheckbox::class=> InvokableFactory::class,
            FormText::class         => InvokableFactory::class,
            FormTextarea::class     => InvokableFactory::class,
            FormLink::class         => InvokableFactory::class,
            FormHidden::class       => InvokableFactory::class,
        ],
        'aliases' => [
            'admin'                 => Admin::class,
            'angular'               => Angular::class,
            'user'                  => User::class,
            'pageHeader'            => PageHeader::class,

            'form'                  => Form::class,
            'formElement'           => FormElement::class,
            'formRow'               => FormRow::class,
            'formFieldset'          => FormFieldset::class,
            'formText'              => FormText::class,
            'formCsrf'              => FormHelper::class,
            'formTextarea'          => FormTextarea::class,
            'formWysiwyg'           => FormWysiwyg::class,
            'formEmail'             => FormHelper::class,
            'formSelect'            => FormSelect::class,
            'formRadio'             => FormRadio::class,
            'formYoutube'           => FormHelper::class,
            'formPassword'          => FormHelper::class,
            'formMultiCheckbox'     => FormMultiCheckbox::class,
            'formDateTime'          => FormDateTime::class,
            'formDate'              => FormDate::class,
            'formCheckbox'          => FormCheckbox::class,
            'formHidden'            => FormHidden::class,
            'formStack'             => FormStack::class,
            'formSwitcher'          => FormCheckbox::class,
            'formLink'              => FormLink::class,
        ],
    ],
];
