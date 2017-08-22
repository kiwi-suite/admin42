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

use Admin42\Mutator\Strategy\FieldsetStrategy;
use Admin42\Mutator\Strategy\LinkStrategy;
use Admin42\Mutator\Strategy\Service\FieldsetStrategyFactory;
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
            FieldsetStrategy::class                 => FieldsetStrategyFactory::class,
        ],
        'aliases' => [
            'stack'                                 => StackStrategy::class,
            'fieldset'                              => FieldsetStrategy::class,
            'link'                                  => LinkStrategy::class,
            'wysiwyg'                               => WysiwygStrategy::class,
        ],
    ],
];
