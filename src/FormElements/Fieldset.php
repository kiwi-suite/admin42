<?php
namespace Admin42\FormElements;

use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;

class Fieldset extends \Zend\Form\Fieldset
{
    use HydratorTrait;

    /**
     * @param FormInterface $form
     * @return mixed|void
     */
    public function prepareElement(FormInterface $form)
    {
        $name = $this->getName();

        $this->setOption("formServiceHash", $form->getOption("formServiceHash"));

        foreach ($this->iterator as $elementOrFieldset) {
            $elementOrFieldset->setOption('originalName', $elementOrFieldset->getName());
            $elementOrFieldset->setOption("formServiceHash", $form->getOption("formServiceHash"));
            $elementOrFieldset->setName($name . '[' . $elementOrFieldset->getName() . ']');

            // Recursively prepare elements
            if ($elementOrFieldset instanceof ElementPrepareAwareInterface) {
                $elementOrFieldset->prepareElement($form);
            }
        }
    }

    public function getHydrator()
    {
        if (!($this->hydrator instanceof HydratorInterface)) {
            $this->setHydrator($this->prepareHydrator($this));
        }
        return parent::getHydrator();
    }
}
