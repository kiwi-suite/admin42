<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Core42\Hydrator\Strategy\DateStrategy;
use Zend\Filter\StringTrim;
use Zend\Filter\ToNull;
use Zend\Hydrator\Strategy\StrategyInterface;

class Date extends \Zend\Form\Element\Date implements StrategyAwareInterface, AngularAwareInterface
{
    use ElementTrait;
    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => ToNull::class],
            ],
            'validators' => $this->getValidators(),
        ];
    }

    /**
     * @return string|StrategyInterface
     */
    public function getStrategy()
    {
        return DateStrategy::class;
    }
}
