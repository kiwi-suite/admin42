<?php
namespace Admin42\FormElements;

use Admin42\FormElements\Service\Factory;
use Core42\Model\GenericModel;
use Ramsey\Uuid\Uuid;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;

class Form extends \Zend\Form\Form
{
    use HydratorTrait;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function prepare()
    {
        if ($this->isPrepared) {
            return $this;
        }

        $this->getInputFilter();

        $this->setOption("formServiceHash", Uuid::uuid4()->toString());

        if ($this->wrapElements()) {
            $this->prepareElement($this);
        } else {
            foreach ($this->getIterator() as $elementOrFieldset) {
                if ($elementOrFieldset->getOption('originalName') === null) {
                    $elementOrFieldset->setOption('originalName', $elementOrFieldset->getName());
                }
                $elementOrFieldset->setOption('formServiceHash', $this->getOption("formServiceHash"));

                if ($elementOrFieldset instanceof FormInterface) {
                    $elementOrFieldset->prepare();
                } elseif ($elementOrFieldset instanceof ElementPrepareAwareInterface) {
                    $elementOrFieldset->prepareElement($this);
                }
            }
        }

        $this->isPrepared = true;
        return $this;
    }

    /**
     * @param FormInterface $form
     * @return mixed|void
     */
    public function prepareElement(FormInterface $form)
    {
        $name = $this->getName();

        foreach ($this->iterator as $elementOrFieldset) {
            if ($form->wrapElements()) {
                if ($elementOrFieldset->getOption('originalName') === null) {
                    $elementOrFieldset->setOption('originalName', $elementOrFieldset->getName());
                }
                $elementOrFieldset->setOption('formServiceHash', $this->getOption("formServiceHash"));
                $elementOrFieldset->setName($name . '[' . $elementOrFieldset->getName() . ']');
            }

            // Recursively prepare elements
            if ($elementOrFieldset instanceof ElementPrepareAwareInterface) {
                $elementOrFieldset->prepareElement($form);
            }
        }
    }

    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!($this->hydrator instanceof HydratorInterface)) {
            $this->setHydrator($this->prepareHydrator($this));
        }
        return parent::getHydrator();
    }

    /**
     *
     * @return Factory
     */
    public function getFormFactory()
    {
        if (null === $this->factory) {
            $this->setFormFactory(new Factory());
        }

        return $this->factory;
    }

    /**
     * @param int $flag
     * @return array|object
     */
    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {
        $data = parent::getData($flag);

        if ($data instanceof GenericModel) {
            $data = $data->toArray();
        }

        return $data;
    }
}
