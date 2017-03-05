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
    'security' => [
        'csp' => [
            'nonce'         => true,
            'style_src'     => ["'unsafe-inline'"],
            'script_src'    => ['https://www.gstatic.com'],
        ],
    ],
];
