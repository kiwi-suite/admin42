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
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\StringLength;

class Text extends Element implements InputProviderInterface, AngularAwareInterface
{
    use ElementTrait;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        $minLength = (!empty($options['minLength'])) ? (int) $options['minLength'] : 0;
        $minLength = max($minLength, 0);
        $this->setOption('minLength', $minLength);

        $maxLength = (!empty($options['maxLength'])) ? (int) $options['maxLength'] : 524288;
        $maxLength = min($maxLength, 524288);
        $this->setOption('maxLength', $maxLength);

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
        return [
            'name' => $this->getName(),
            'required' => $this->isRequired(),
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => ['max' => $this->getOption("maxLength"), 'min' => $this->getOption("minLength")]
                ],
            ],
        ];
    }
}
