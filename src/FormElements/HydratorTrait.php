<?php
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
