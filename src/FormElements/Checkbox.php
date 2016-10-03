<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Filter\StringTrim;
use Zend\Form\Element;
use Zend\Validator\InArray;

class Checkbox extends Element implements AngularAwareInterface
{
    use ElementTrait;

    /**
     * @var string
     */
    protected $uncheckedValue = 'false';

    /**
     * @var string
     */
    protected $checkedValue = 'true';

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['checkedValue'])) {
            $this->setCheckedValue($options['checkedValue']);
        }

        if (isset($options['uncheckedValue'])) {
            $this->setUncheckedValue($options['uncheckedValue']);
        }

        return $this;
    }

    /**
     * Set the value to use when checkbox is unchecked
     *
     * @param $uncheckedValue
     * @return Checkbox
     */
    public function setUncheckedValue($uncheckedValue)
    {
        $this->uncheckedValue = $uncheckedValue;

        return $this;
    }

    /**
     * Get the value to use when checkbox is unchecked
     *
     * @return string
     */
    public function getUncheckedValue()
    {
        return $this->uncheckedValue;
    }

    /**
     * Set the value to use when checkbox is checked
     *
     * @param $checkedValue
     * @return Checkbox
     */
    public function setCheckedValue($checkedValue)
    {
        $this->checkedValue = $checkedValue;
        return $this;
    }

    /**
     * Get the value to use when checkbox is checked
     *
     * @return string
     */
    public function getCheckedValue()
    {
        return $this->checkedValue;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return true;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => $this->isRequired(),
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => ['haystack' => [$this->checkedValue, $this->uncheckedValue]]
                ],
            ],
        ];
    }
}
