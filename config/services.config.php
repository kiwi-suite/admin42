<?php
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

