<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
