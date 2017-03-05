<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;

class FormCheckbox extends FormHelper
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
