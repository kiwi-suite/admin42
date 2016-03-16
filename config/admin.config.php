<?php
namespace Admin42;

return [
    'admin' => [
        'job_auth' => '',
        'angular' => [
            'modules' => ['admin42']
        ],

        'view_helpers' => [
            'invokables' => [
                'form'                    => 'Admin42\View\Helper\Form\Form',
                'formbutton'              => 'Admin42\View\Helper\Form\FormButton',
                'formcaptcha'             => 'Admin42\View\Helper\Form\FormCaptcha',
                'captchadumb'             => 'Admin42\View\Helper\Form\Captcha\Dumb',
                'formcaptchadumb'         => 'Admin42\View\Helper\Form\Captcha\Dumb',
                'captchafiglet'           => 'Admin42\View\Helper\Form\Captcha\Figlet',
                'formcaptchafiglet'       => 'Admin42\View\Helper\Form\Captcha\Figlet',
                'captchaimage'            => 'Admin42\View\Helper\Form\Captcha\Image',
                'formcaptchaimage'        => 'Admin42\View\Helper\Form\Captcha\Image',
                'captcharecaptcha'        => 'Admin42\View\Helper\Form\Captcha\ReCaptcha',
                'formcaptcharecaptcha'    => 'Admin42\View\Helper\Form\Captcha\ReCaptcha',
                'formcheckbox'            => 'Admin42\View\Helper\Form\FormCheckbox',
                'formcollection'          => 'Admin42\View\Helper\Form\FormCollection',
                'formcolor'               => 'Admin42\View\Helper\Form\FormColor',
                'formdate'                => 'Admin42\View\Helper\Form\FormDate',
                'formdatetime'            => 'Admin42\View\Helper\Form\FormDateTime',
                'formdatetimelocal'       => 'Admin42\View\Helper\Form\FormDateTimeLocal',
                'formdatetimeselect'      => 'Admin42\View\Helper\Form\FormDateTimeSelect',
                'formdateselect'          => 'Admin42\View\Helper\Form\FormDateSelect',
                'formelement'             => 'Admin42\View\Helper\Form\FormElement',
                'formelementerrors'       => 'Admin42\View\Helper\Form\FormElementErrors',
                'formemail'               => 'Admin42\View\Helper\Form\FormEmail',
                'formfile'                => 'Admin42\View\Helper\Form\FormFile',
                'formfileapcprogress'     => 'Admin42\View\Helper\Form\File\FormFileApcProgress',
                'formfilesessionprogress' => 'Admin42\View\Helper\Form\File\FormFileSessionProgress',
                'formfileuploadprogress'  => 'Admin42\View\Helper\Form\File\FormFileUploadProgress',
                'formhidden'              => 'Admin42\View\Helper\Form\FormHidden',
                'formimage'               => 'Admin42\View\Helper\Form\FormImage',
                'forminput'               => 'Admin42\View\Helper\Form\FormInput',
                'formlabel'               => 'Admin42\View\Helper\Form\FormLabel',
                'formmonth'               => 'Admin42\View\Helper\Form\FormMonth',
                'formmonthselect'         => 'Admin42\View\Helper\Form\FormMonthSelect',
                'formmulticheckbox'       => 'Admin42\View\Helper\Form\FormMultiCheckbox',
                'formnumber'              => 'Admin42\View\Helper\Form\FormNumber',
                'formpassword'            => 'Admin42\View\Helper\Form\FormPassword',
                'formradio'               => 'Admin42\View\Helper\Form\FormRadio',
                'formrange'               => 'Admin42\View\Helper\Form\FormRange',
                'formreset'               => 'Admin42\View\Helper\Form\FormReset',
                'formrow'                 => 'Admin42\View\Helper\Form\FormRow',
                'formsearch'              => 'Admin42\View\Helper\Form\FormSearch',
                'formselect'              => 'Admin42\View\Helper\Form\FormSelect',
                'formsubmit'              => 'Admin42\View\Helper\Form\FormSubmit',
                'formtel'                 => 'Admin42\View\Helper\Form\FormTel',
                'formtext'                => 'Admin42\View\Helper\Form\FormText',
                'formtextarea'            => 'Admin42\View\Helper\Form\FormTextarea',
                'formtime'                => 'Admin42\View\Helper\Form\FormTime',
                'formurl'                 => 'Admin42\View\Helper\Form\FormUrl',
                'formweek'                => 'Admin42\View\Helper\Form\FormWeek',
                'formfileselect'          => 'Admin42\View\Helper\Form\FileSelect',
                'formwysiwyg'             => 'Admin42\View\Helper\Form\FormWysiwyg',
            ],
            'factories' => [
                'admin'                 => 'Admin42\View\Helper\Service\AdminFactory',
            ],
        ],

        'display-timezone' => 'Europe/Vienna',

        'whitelabel' => [
            'topbar-title'  => 'kiw<span class="text-r42">i</span>42',
            'logo-icon'     => 'assets/admin/core/images/logo-icon.png',
            'logo-lg'       => 'assets/admin/core/images/logo-lg.png',
            'logo-xs'       => 'assets/admin/core/images/logo-xs.png',
        ],
    ],
];
