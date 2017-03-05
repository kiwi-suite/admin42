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
