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

class FormMultiCheckbox extends FormHelper
{
    public function getValue(AngularAwareInterface $element)
    {
        $value = $element->getValue();
        if (!is_array($value)) {
            $value = [];
        }

        return array_values($value);
    }

    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $translateHelper = $this->getView()->plugin('translate');

        $elementData = parent::getElementData($element, $angularNameRendering);

        $valueOptions = [];
        foreach ($element->getValueOptions() as $id => $value) {
            $valueOptions[] = [
                'id'    => $id,
                'label' => $translateHelper($value, 'admin'),
            ];
        }
        $elementData['valueOptions'] = $valueOptions;

        return $elementData;
    }
}
