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

use Admin42\Link\Adapter\ExternalLink;
use Admin42\View\Helper\Service\LinkFactory;

return [
    'migration' => [
        'directory'     => [
            __NAMESPACE__ => __DIR__ . '/../data/migrations',
        ],
    ],

    'link' => [
        'adapter' => [
            'external' => ExternalLink::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'link'             => LinkFactory::class,
        ],
    ],
];
