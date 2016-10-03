<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;

class FormSwitcher extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);
        $elementData['uncheckedValue'] = (string) $element->getUncheckedValue();
        $elementData['checkedValue'] = (string) $element->getCheckedValue();

        return $elementData;
    }
}
