<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Form;

use Zend\Form\ElementInterface;

class FormCheckbox extends FormHelper
{
    /**
     * @param ElementInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(ElementInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);
        $elementData['uncheckedValue'] = (string) $element->getUncheckedValue();
        $elementData['checkedValue'] = (string) $element->getCheckedValue();

        return $elementData;
    }
}
