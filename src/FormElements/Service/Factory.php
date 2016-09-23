<?php
namespace Admin42\FormElements\Service;

use Admin42\FormElements\Fieldset;
use Admin42\FormElements\Form;
use Zend\Form\ElementInterface;
use Zend\Form\FieldsetInterface;

class Factory extends \Core42\Form\Service\Factory
{
    public function create($spec)
    {
        $spec['attributes'] = [];
        $spec['options'] = [];

        if (!empty($spec['value'])) {
            $spec['attributes']['value'] = $spec['value'];
            unset($spec['value']);
        }

        foreach ($spec as $name => $value) {
            if (in_array($name, ['type', 'name', 'options', 'attributes'])) {
                continue;
            }

            $spec['options'][$name] = $value;
            unset($spec[$name]);
        }

        return parent::create($spec);
    }

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
     * @param FieldsetInterface $fieldset
     * @param array|\ArrayAccess|\Traversable $spec
     * @return ElementInterface|FieldsetInterface
     */
    public function configureFieldset(FieldsetInterface $fieldset, $spec)
    {
        $spec     = $this->validateSpecification($spec, __METHOD__);
        $fieldset = $this->configureElement($fieldset, $spec);

        if (isset($spec['options']['elements'])) {
            $this->prepareAndInjectElements($spec['options']['elements'], $fieldset, __METHOD__);
        }

        $factory = (isset($spec['factory']) ? $spec['factory'] : $this);
        $this->prepareAndInjectFactory($factory, $fieldset, __METHOD__);

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
