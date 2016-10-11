<?php
namespace Admin42;

return [
    'admin' => [
        'timezone' => 'Europe/Vienna',
        'locale' => 'en-US',

        'assets' => [
            'admin42' => [
                'js' => [
                    'vendor'    => '/js/vendor.min.js',
                    'app'       => '/js/admin42.min.js',
                    'tinymce'   => '/tinymce/tinymce.min.js'
                ],
                'css' => [
                    'main'      => '/css/admin42.min.css',
                ],
            ],
        ],
    ],
];
