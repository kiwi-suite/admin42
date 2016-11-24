<?php
namespace Admin42;

return [
    'project' => [
        'project_name' => __NAMESPACE__,
        'email_subject_prefix' => __NAMESPACE__ . ': ',
        'email_from' => 'developer@raum42.at',
        'project_base_url' => '',

        'admin_login_captcha' => false,
        'admin_login_captcha_options' => ['sitekey' => '', 'secret' => '']
    ],
];
