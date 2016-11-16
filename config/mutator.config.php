<?php
namespace Admin42;

use Admin42\Mutator\Strategy\Service\StackStrategyFactory;
use Admin42\Mutator\Strategy\StackStrategy;

return [
    'mutator' => [
        'factories' => [
            StackStrategy::class                    => StackStrategyFactory::class,
        ],
        'aliases' => [
            'stack'                                 => StackStrategy::class,
        ],
    ],
];
