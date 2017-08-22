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

namespace Admin42\Mutator\Strategy;

use Core42\Hydrator\Mutator\Mutator;
use Core42\Hydrator\Mutator\Strategy\StrategyInterface;
use Core42\Model\GenericModel;

class FieldsetStrategy implements StrategyInterface
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
        if (empty($spec['elements'])) {
            return [];
        }

        $fieldsetValues = $this->mutator->hydrate($value, $spec['elements']);

        return new GenericModel($fieldsetValues);
    }
}
