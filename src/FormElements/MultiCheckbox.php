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

use Core42\Hydrator\Strategy\ArrayStrategy;
use Zend\Filter\Callback;
use Zend\Form\Element;
use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Explode;
use Zend\Validator\InArray;

class MultiCheckbox extends Element implements AngularAwareInterface, InputProviderInterface, StrategyAwareInterface
{
    use ElementTrait;

    /**
     * @var array
     */
    protected $valueOptions = [];

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['values'])) {
            $this->setValueOptions($options['values']);
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
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        $haystack = \array_keys($this->getValueOptions());

        return [
            'name' => $this->getName(),
            'required' => $this->isRequired(),
            'filters' => [
                [
                    'name' => Callback::class,
                    'options' => [
                        'callback' => function ($value) {
                            if (!\is_array($value)) {
                                return [];
                            }

                            return $value;
                        },
                    ],
                ],
            ],
            'validators' => [
                [
                    'name' => Explode::class,
                    'options' => [
                        'valueDelimiter' => null,
                        'validator' => [
                            'name' => InArray::class,
                            'options' => ['haystack' => $haystack],
                        ],
                    ],
                ],

            ],
        ];
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setValue($value)
    {
        if (!\is_array($value)) {
            $value = [];
        }

        $this->value = \array_values($value);

        return $this;
    }

    /**
     * @return string|StrategyInterface
     */
    public function getStrategy()
    {
        return ArrayStrategy::class;
    }
}
