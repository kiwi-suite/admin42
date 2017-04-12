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


namespace Admin42\FormElements;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\InArray;

class Radio extends Element implements AngularAwareInterface, InputProviderInterface
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
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => ['haystack' => $haystack],
                ],
            ],
        ];
    }
}
