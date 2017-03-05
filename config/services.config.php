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

use Admin42\Link\Adapter\ExternalLink;
use Admin42\Link\LinkProvider;
use Admin42\Link\Service\LinkProviderFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            LinkProvider::class             => LinkProviderFactory::class,
            ExternalLink::class             => InvokableFactory::class,
        ],
    ],
];
