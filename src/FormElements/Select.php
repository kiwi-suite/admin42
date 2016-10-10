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

use Zend\Filter\StringTrim;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\InArray;

class Select extends Element implements AngularAwareInterface, InputProviderInterface
{
    use ElementTrait;

    /**
     * @var array
     */
    protected $valueOptions = [];

    /**
     * @var null
     */
    protected $emptyValue = null;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['values'])) {
            $this->setValueOptions($options['values']);
        }

        if (!empty($options['emptyValue'])) {
            $this->setEmptyValue($options['emptyValue']);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValueOptions()
    {
        return $this->valueOptions;
    }

    /**
     * @param array $valueOptions
     * @return $this
     */
    public function setValueOptions(array $valueOptions)
    {
        $this->valueOptions = $valueOptions;

        return $this;
    }

    /**
     * @param array $emptyValue
     */
    public function setEmptyValue(array $emptyValue)
    {
        $this->emptyValue = $emptyValue;
    }

    /**
     * @return array|null
     */
    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        $haystack = array_keys($this->getValueOptions());
        if ($this->getEmptyValue() !== null) {
            $haystack[] = key($this->getEmptyValue());
        }

        return [
            'name' => $this->getName(),
            'required' => $this->isRequired(),
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => ['haystack' => $haystack],
                ],
            ],
        ];
    }
}
