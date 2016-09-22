<?php
namespace Admin42;

return [
    'admin' => [
        'timezone' => 'Europe/Vienna',
        'locale' => 'en-US',

        'assets' => [
            __NAMESPACE__ => [
                'js' => [
                    'vendor'    => '/assets/admin/admin42/js/vendor.min.js',
                    'app'       => '/assets/admin/admin42/js/admin42.min.js',
                    'tinymce'   => '/assets/admin/admin42/tinymce/tinymce.min.js'
                ],
                'css' => [
                    'main'      => '/assets/admin/admin42/css/admin42.min.css',
                ],
            ],
        ],
    ],
];
