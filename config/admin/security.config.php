<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */

namespace Admin42;

return [
    'security' => [
        'csp' => [
            'nonce'         => true,
            'style_src'     => ["'unsafe-inline'"],
            'script_src'    => ['https://www.gstatic.com'],
        ],
    ],
];
