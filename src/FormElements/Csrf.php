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

use Zend\Filter\StringTrim;
use Zend\Form\Element;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Csrf as CsrfValidator;

class Csrf extends Element implements AngularAwareInterface, InputProviderInterface, ElementPrepareAwareInterface
{
    use ElementTrait;

    /**
     * @var array
     */
    protected $csrfValidatorOptions = [];

    /**
     * @var CsrfValidator
     */
    protected $csrfValidator;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['csrfOptions'])) {
            $this->setCsrfValidatorOptions($options['csrfOptions']);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCsrfValidatorOptions()
    {
        return $this->csrfValidatorOptions;
    }

    /**
     * @param  array $options
     * @return Csrf
     */
    public function setCsrfValidatorOptions(array $options)
    {
        $this->csrfValidatorOptions = $options;

        return $this;
    }

    /**
     * Prepare the form element
     */
    public function prepareElement(FormInterface $form)
    {
        $this->getCsrfValidator()->getHash(true);
    }

    /**
     * @return CsrfValidator
     */
    public function getCsrfValidator()
    {
        if (null === $this->csrfValidator) {
            $csrfOptions = $this->getCsrfValidatorOptions();
            $csrfOptions = \array_merge($csrfOptions, ['name' => $this->getName()]);
            $this->csrfValidator = new CsrfValidator($csrfOptions);
        }

        return $this->csrfValidator;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                $this->getCsrfValidator(),
            ],
        ];
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $validator = $this->getCsrfValidator();

        return $validator->getHash();
    }
}
