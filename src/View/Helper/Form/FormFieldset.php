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


namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;

class FormFieldset extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @return array
     */
    public function getValue(AngularAwareInterface $element)
    {
        return [];
    }

    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);

        $elements = [];
        foreach ($element->getIterator() as $elementOrFieldset) {
            $name = (new \ReflectionClass($elementOrFieldset))->getShortName();

            if (!$this->getView()->getHelperPluginManager()->has('form' . $name)) {
                continue;
            }
            $formHelper = $this->getView()->plugin('form' . $name);
            if (!($formHelper instanceof AngularHelperInterface)) {
                continue;
            }

            $elements[] = [
                'directive' => $formHelper->getAngularDirective($elementOrFieldset),
                'elementDataId' => $this->getAngularHelper()->generateJsonTemplate(
                    $formHelper->getElementData($elementOrFieldset),
                    'element/form/value/'
                ),
            ];
        }

        $elementData['elements'] = $elements;
        $elementData['showLabel'] = $element->getShowLabel();
        $elementData['collapseAble'] = $element->getCollapseAble();

        return $elementData;
    }
}
