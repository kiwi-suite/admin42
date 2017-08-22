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
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../../data/language/',
                'pattern' => '%s.php',
                'text_domain' => 'admin',
            ],
        ],
    ],
];
