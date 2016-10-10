<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\FormElements;

use Core42\Hydrator\BaseHydrator;
use Core42\Hydrator\Strategy\StringStrategy;
use Zend\Form\FieldsetInterface;

trait HydratorTrait
{
    /**
     * @var BaseHydrator
     */
    protected $hydratorPrototype;

    /**
     * @param BaseHydrator $hydratorPrototype
     */
    public function setHydratorPrototype(BaseHydrator $hydratorPrototype)
    {
        $this->hydratorPrototype = $hydratorPrototype;
    }

    /**
     * @param FieldsetInterface $fieldset
     * @return BaseHydrator
     */
    protected function prepareHydrator(FieldsetInterface $fieldset)
    {
        $strategies = [];
        foreach ($fieldset->getIterator() as $elementOrFieldset) {
            if ($elementOrFieldset instanceof FieldsetInterface) {
                continue;
            }
            $strategy = StringStrategy::class;
            if ($elementOrFieldset instanceof StrategyAwareInterface) {
                $strategy = $elementOrFieldset->getStrategy();
            }
            $strategies[$elementOrFieldset->getName()] = $strategy;
        }

        $this->hydratorPrototype->addStrategies($strategies);

        return $this->hydratorPrototype;
    }
}
