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

return [
    'admin' => [
        'timezone' => 'Europe/Vienna',
        'locale' => 'en-US',

        'assets' => [
            'admin42' => [
                'js' => [
                    'vendor'    => '/js/vendor.min.js',
                    'app'       => '/js/admin42.min.js',
                    'tinymce'   => '/tinymce/tinymce.min.js',
                ],
                'css' => [
                    'main'      => '/css/admin42.min.css',
                ],
            ],
        ],

        'login_captcha' => true,
        'login_captcha_options' => [
            'sitekey' => '',
            'secret' => '',
        ],
    ],
];
