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

namespace Admin42\Mutator\Strategy;

use Admin42\Stdlib\Stack;
use Core42\Hydrator\Mutator\Mutator;
use Core42\Hydrator\Mutator\Strategy\StrategyInterface;

class StackStrategy implements StrategyInterface
{
    /**
     * @var Mutator
     */
    protected $mutator;

    /**
     * BlockStrategy constructor.
     * @param Mutator $mutator
     */
    public function __construct(
        Mutator $mutator
    ) {
        $this->mutator = $mutator;
    }

    /**
     * @param mixed $value
     * @param array $spec
     * @return mixed
     */
    public function hydrate($value, array $spec = [])
    {
        if (empty($spec['sets'])) {
            return [];
        }

        $prototypes = [];
        foreach ($spec['sets'] as $set) {
            if (!isset($set['name'])) {
                continue;
            }
            $elements = [];
            if (\is_array($set['elements'])) {
                $elements = $set['elements'];
            }
            $prototypes[$set['name']] = $elements;
        }

        $stack = [];
        foreach ($value as $id => $stackSpec) {
            if (empty($stackSpec['__type__'])) {
                continue;
            }

            if (!isset($prototypes[$stackSpec['__type__']])) {
                continue;
            }

            $stackValues = $this->mutator->hydrate($stackSpec, $prototypes[$stackSpec['__type__']]);
            if (empty($stackValues) || !\is_array($stackValues)) {
                $stackValues = [];
            }

            $stack[] = new Stack($id, $stackSpec['__type__'], $stackValues);
        }

        return $stack;
    }
}
