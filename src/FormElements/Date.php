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

use Core42\Hydrator\Strategy\DateStrategy;
use Zend\Filter\StringTrim;
use Zend\Filter\ToNull;
use Zend\Form\Element;
use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\InputFilter\InputProviderInterface;

class Date extends Element implements StrategyAwareInterface, AngularAwareInterface, InputProviderInterface
{
    use ElementTrait;

    /**
     * @var string
     */
    protected $format = 'Y-m-d';

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => $this->isRequired(),
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => ToNull::class],
            ],
            'validators' => [
                [
                    'name' => \Zend\Validator\Date::class,
                    'options' => [
                        'format' => $this->getFormat(),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param bool $returnFormattedValue
     * @return mixed
     */
    public function getValue($returnFormattedValue = true)
    {
        $value = parent::getValue();
        if (!$value instanceof \DateTime || !$returnFormattedValue) {
            return $value;
        }
        $format = $this->getFormat();

        return $value->format($format);
    }

    /**
     * @return string|StrategyInterface
     */
    public function getStrategy()
    {
        return DateStrategy::class;
    }
}
