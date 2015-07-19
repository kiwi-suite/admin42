<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Form\ElementInterface;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\Fieldset;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;
use Traversable;

class Dynamic extends Fieldset
{
    /**
     * @var string
     */
    protected $templatePlaceholder = '{{ element.internIndex }}';

    /**
     * @var string
     */
    protected $templatePlaceholderName = "";

    /**
     * @var array
     */
    protected $initialElements = [];

    /**
     * @var int
     */
    protected $lastChildIndex = -1;

    /**
     * @var array
     */
    protected $targetElements = [];

    /**
     * @var array
     */
    protected $templateElements;

    /**
     * @var bool
     */
    protected $shouldCreateChildrenOnPrepareElement = true;

    /**
     * @return string
     */
    public function getType()
    {
        return 'dynamic';
    }

    /**
     * @param array $initialElements
     * @return $this
     */
    public function setInitialElements(array $initialElements)
    {
        $this->initialElements = $initialElements;

        return $this;
    }

    /**
     * @return array
     */
    public function getInitialElements()
    {
        return $this->initialElements;
    }

    /**
     * Prepare the collection by adding a dummy template element if the user want one
     *
     * @param  FormInterface $form
     * @return mixed|void
     */
    public function prepareElement(FormInterface $form)
    {
        if ($this->shouldCreateChildrenOnPrepareElement === true) {
            if (count($this->getInitialElements()) > 0) {
                foreach ($this->initialElements as $initialElement) {
                    $this->addNewTargetElementInstance($initialElement, ++$this->lastChildIndex);
                }
            }
        }

        parent::prepareElement($form);

        $name = $this->getName();
        $templateElements = $this->getTemplateElements();
        foreach ($templateElements as $elementOrFieldset) {
            $elementOrFieldset->setName($name . '[' . $elementOrFieldset->getName() . ']');

            // Recursively prepare elements
            if ($elementOrFieldset instanceof ElementPrepareAwareInterface) {
                $elementOrFieldset->prepareElement($form);
            }
        }
    }

    /**
     * Populate values
     *
     * @param array|Traversable $data
     * @throws \Zend\Form\Exception\InvalidArgumentException
     * @throws \Zend\Form\Exception\DomainException
     * @return void
     */
    public function populateValues($data)
    {
        if (!is_array($data) && !$data instanceof Traversable) {
            throw new InvalidArgumentException(sprintf(
                '%s expects an array or Traversable set of data; received "%s"',
                __METHOD__,
                (is_object($data) ? get_class($data) : gettype($data))
            ));
        }

        // Can't do anything with empty data
        if (empty($data)) {
            return;
        }

        uasort($data, function ($value1, $value2) {
            if (!array_key_exists('dynamic_index', $value1) || !array_key_exists('dynamic_index', $value2)) {
                return 0;
            }
            if ($value1['dynamic_index'] == $value2['dynamic_index']) {
                return 0;
            }
            return ($value1['dynamic_index'] < $value2['dynamic_index']) ? -1 : 1;
        });
        $newData = [];
        foreach ($data as $info) {
            if (!array_key_exists('dynamic_deleted', $info) || $info['dynamic_deleted'] == 'true') {
                continue;
            }
            $newData[] = $info;
        }

        $data = $newData;

        // Check to see if elements have been replaced or removed
        foreach ($this->iterator as $name => $elementOrFieldset) {
            if (isset($data[$name])) {
                continue;
            }

            $this->remove($name);
        }

        foreach ($data as $key => $value) {
            if ($this->has($key)) {
                $elementOrFieldset = $this->get($key);
            } else {
                if (!array_key_exists('dynamic_type', $value)) {
                    throw new InvalidArgumentException(sprintf(
                        '%s expects array items with an attribute "dynamic_type"',
                        __METHOD__
                    ));
                }
                $elementOrFieldset = $this->addNewTargetElementInstance($value['dynamic_type'], $key);

                if ($key > $this->lastChildIndex) {
                    $this->lastChildIndex = $key;
                }
            }

            if ($elementOrFieldset instanceof FieldsetInterface) {
                $elementOrFieldset->populateValues($value);
            } else {
                $elementOrFieldset->setAttribute('value', $value);
            }
        }
    }

    /**
     * Add a new instance of the target element
     *
     * @param string $name
     * @return ElementInterface
     */
    protected function addNewTargetElementInstance($name, $formName)
    {
        $this->shouldCreateChildrenOnPrepareElement = false;

        $elementOrFieldset = clone ($this->targetElements[$name]);
        $elementOrFieldset->setName($formName);
        $elementOrFieldset->setOption("internIndex", $formName);

        $this->add($elementOrFieldset);

        return $elementOrFieldset;
    }

    /**
     * @param ElementInterface|array|Traversable $elementOrFieldset
     * @return $this
     * @throws \Zend\Form\Exception\InvalidArgumentException
     */
    public function addTargetElement($name, $elementOrFieldset)
    {
        if (is_array($elementOrFieldset)
            || ($elementOrFieldset instanceof Traversable && !$elementOrFieldset instanceof ElementInterface)
        ) {
            $factory = $this->getFormFactory();
            $elementOrFieldset = $factory->create($elementOrFieldset);
        }

        if (!$elementOrFieldset instanceof FieldsetInterface) {
            throw new InvalidArgumentException(sprintf(
                '%s requires that $elementOrFieldset be an object implementing %s; received "%s"',
                __METHOD__,
                __NAMESPACE__ . '\FieldsetInterface',
                (is_object($elementOrFieldset) ? get_class($elementOrFieldset) : gettype($elementOrFieldset))
            ));
        }

        $elementOrFieldset->setOption('dynamic_type', $name);

        $this->targetElements[$name] = $elementOrFieldset;
    }

    /**
     * @return array
     */
    public function getTemplateElements()
    {
        if (is_array($this->templateElements)) {
            return $this->templateElements;
        }

        $this->templateElements = [];
        foreach ($this->targetElements as $name => $element) {
            $newElement = clone $element;
            $newElement->setName($this->templatePlaceholder);
            $this->templateElements[] = $newElement;
        }

        return $this->templateElements;
    }

    /**
     * @return string
     */
    public function getTemplatePlaceholder()
    {
        return $this->templatePlaceholder;
    }
}
