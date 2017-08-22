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

        $hydrator = clone $this->hydratorPrototype;

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

        $hydrator->addStrategies($strategies);

        return $hydrator;
    }
}
