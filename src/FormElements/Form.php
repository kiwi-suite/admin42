<?php
namespace Admin42\FormElements;

use Ramsey\Uuid\Uuid;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;

class Form extends \Zend\Form\Form
{
    use HydratorTrait;

    /**
     * @param FormInterface $form
     * @return mixed|void
     */
    public function prepareElement(FormInterface $form)
    {
        $name = $this->getName();
        $this->setOption("formServiceHash", Uuid::uuid4()->toString());

        foreach ($this->iterator as $elementOrFieldset) {
            if ($form->wrapElements()) {
                $elementOrFieldset->setOption('originalName', $elementOrFieldset->getName());
                $elementOrFieldset->setOption('formServiceHash', $this->getOption("formServiceHash"));
                $elementOrFieldset->setName($name . '[' . $elementOrFieldset->getName() . ']');
            }

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
