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
