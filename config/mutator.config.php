<?php
namespace Admin42;

use Admin42\Mutator\Strategy\LinkStrategy;
use Admin42\Mutator\Strategy\Service\LinkStrategyFactory;
use Admin42\Mutator\Strategy\Service\StackStrategyFactory;
use Admin42\Mutator\Strategy\Service\WysiwygStrategyFactory;
use Admin42\Mutator\Strategy\StackStrategy;
use Admin42\Mutator\Strategy\WysiwygStrategy;

return [
    'mutator' => [
        'factories' => [
            StackStrategy::class                    => StackStrategyFactory::class,
            LinkStrategy::class                     => LinkStrategyFactory::class,
            WysiwygStrategy::class                  => WysiwygStrategyFactory::class,
        ],
        'aliases' => [
            'stack'                                 => StackStrategy::class,
            'link'                                  => LinkStrategy::class,
            'wysiwyg'                               => WysiwygStrategy::class,
        ],
    ],
];
