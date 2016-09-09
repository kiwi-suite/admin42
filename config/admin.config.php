<?php
namespace Admin42;

return [
    'admin' => [
        'job_auth' => '',
        'angular'  => [
            'modules' => ['admin42']
        ],

        'session_config' => [
            'name' => 'kiwi42',
            'use_trans_sid' => false,
            'use_cookies' => true,
            'use_only_cookies' => true,
            'cookie_httponly' => true,
        ],

        'display-timezone' => 'Europe/Vienna',

        'whitelabel' => [
            'show-topbar-title'         => true,
            'topbar-title'              => 'kiw<span class="text-r42">i</span>42',
            'logo-icon'                 => 'assets/admin/admin42/images/logo-icon.png', // topbar -> light
            'logo-lg'                   => 'assets/admin/admin42/images/logo-lg.png', // login top -> dark
            'logo-xs'                   => 'assets/admin/admin42/images/logo-icon.png', // sidebar bottom -> light
            'logo-xs-dark'              => 'assets/admin/admin42/images/logo-lg.png', // login footer -> dark
            'sidebar-bottom-text'       => 'kiw<span class="text-r42">i</span>42&nbsp;&copy;&nbsp;raum42 OG',
            'sidebar-bottom-link'       => 'https://www.raum42.at',
            'sidebar-bottom-link-title' => 'raum42 OG',
        ],

        'google_map' => [
            'api_key' => 'AIzaSyBbVr_HG3DZB2PizS3ZRrX95HYEWfS3m6c',
        ],
    ],
];
