<?php
namespace Admin42\FormElements\Service;

use Admin42\FormElements\AngularAwareInterface;
use Admin42\FormElements\Fieldset;
use Admin42\FormElements\Form;
use Zend\Form\ElementInterface;
use Zend\Form\FieldsetInterface;

class Factory extends \Core42\Form\Service\Factory
{

    /**
     * Create an element
     *
     * @param  array $spec
     * @return ElementInterface
     */
    public function createElement($spec)
    {
        return $this->create($spec);
    }

    /**
     * Create a fieldset
     *
     * @param  array $spec
     * @return ElementInterface
     */
    public function createFieldset($spec)
    {
        if (!isset($spec['type'])) {
            $spec['type'] = Fieldset::class;
        }

        return $this->create($spec);
    }

    /**
     * Create a form
     *
     * @param  array $spec
     * @return ElementInterface
     */
    public function createForm($spec)
    {
        if (!isset($spec['type'])) {
            $spec['type'] = Form::class;
        }

        return $this->create($spec);
    }

    /**
     * @param ElementInterface $element
     * @param array|\ArrayAccess|\Traversable $spec
     * @return ElementInterface
     */
    public function configureElement(ElementInterface $element, $spec)
    {
        if (!($element instanceof AngularAwareInterface)) {
            return parent::configureElement($element, $spec);
        }
        $spec['attributes'] = [];
        $spec['options'] = [];

        if (!empty($spec['name'])) {
            $element->setName($spec['name']);
            unset($spec['name']);
        }

        if (!empty($spec['value'])) {
            $element->setValue($spec['value']);
            unset($spec['value']);
        }

        $readonly = (isset($spec['readonly']) && $spec['readonly'] === true);
        $element->setReadonly($readonly);
        unset($spec['readonly']);

        $required = (isset($spec['required']) && $spec['required'] === true);
        $element->setRequired($required);
        unset($spec['required']);

        if (isset($spec['description'])) {
            $element->setDescription($spec['description']);
            unset($spec['description']);
        }

        if (isset($spec['template'])) {
            $element->setTemplate($spec['template']);
            unset($spec['template']);
        }

        if (isset($spec['label'])) {
            $element->setLabel($spec['label']);
            unset($spec['label']);
        }

        $element->setOptions($spec);

        return $element;
    }

    /**
     * @param FieldsetInterface $fieldset
     * @param array|\ArrayAccess|\Traversable $spec
     * @return ElementInterface|FieldsetInterface
     */
    public function configureFieldset(FieldsetInterface $fieldset, $spec)
    {
        $spec     = $this->validateSpecification($spec, __METHOD__);
        $fieldset = $this->configureElement($fieldset, $spec);

        if (isset($spec['elements'])) {
            $this->prepareAndInjectElements($spec['elements'], $fieldset, __METHOD__);
        }

        $this->prepareAndInjectFactory($this, $fieldset, __METHOD__);

        return $fieldset;
    }

    /**
     * Takes a list of element specifications, creates the elements, and injects them into the provided fieldset
     *
     * @param  array|Traversable|ArrayAccess $elements
     * @param  FieldsetInterface $fieldset
     * @param  string $method Method invoking this one (for exception messages)
     * @return void
     */
    protected function prepareAndInjectElements($elements, FieldsetInterface $fieldset, $method)
    {
        $elements = $this->validateSpecification($elements, $method);

        foreach ($elements as $elementSpecification) {
            if (null === $elementSpecification) {
                continue;
            }

            $fieldset->add($elementSpecification);
        }
    }
}
