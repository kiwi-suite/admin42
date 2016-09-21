<?php
namespace Admin42\View\Helper\Form;

use Ramsey\Uuid\Uuid;
use Zend\Form\ElementInterface;

class FormFieldset extends FormHelper
{

    /**
     * @param ElementInterface $element
     * @return array
     */
    public function getValue(ElementInterface $element)
    {
        return [];
    }

    /**
     * @param ElementInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(ElementInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);

        $elements = [];
        foreach ($element->getIterator() as $elementOrFieldset) {
            $name = (new \ReflectionClass($elementOrFieldset))->getShortName();

            if (!$this->getView()->getHelperPluginManager()->has('form'.$name)) {
                continue;
            }
            $formHelper = $this->getView()->plugin('form'.$name);
            if (!($formHelper instanceof AngularHelperInterface)) {
                continue;
            }

            $formHelper->addElementTemplate($elementOrFieldset);
            $elements[] = [
                'directive' => $formHelper->getAngularDirective($elementOrFieldset),
                'elementData' => $this->getAngularHelper()->generateJsonTemplate(
                    $formHelper->getElementData($elementOrFieldset),
                    'element/form/value/'
                ),
            ];
        }

        $elementData['elements'] = $elements;

        return $elementData;
    }
}
