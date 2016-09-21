<?php
namespace Admin42\View\Helper\Form;

use Zend\Form\ElementInterface;

interface AngularHelperInterface
{
    /**
     * @param ElementInterface $element
     * @return string
     */
    public function getAngularDirective(ElementInterface $element);

    /**
     * @param ElementInterface $element
     * @return mixed
     */
    public function getValue(ElementInterface $element);

    /**
     * @param ElementInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(ElementInterface $element, $angularNameRendering);

    /**
     * @param ElementInterface $element
     */
    public function addElementTemplate(ElementInterface $element);
}
