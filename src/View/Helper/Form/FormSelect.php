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

class FormSelect extends FormHelper
{
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
        if ($element->getEmptyValue() !== null) {
            $id = \key($element->getEmptyValue());
            $valueOptions[] = [
                'id' => $id,
                'label' => $translateHelper($element->getEmptyValue()[$id], 'admin'),
            ];
        }
        foreach ($element->getValueOptions() as $id => $value) {
            $valueOptions[] = [
                'id'    => $id,
                'label' => $translateHelper($value, 'admin'),
            ];
        }
        $elementData['valueOptions'] = $valueOptions;
        $elementData['emptyValue'] = null;
        if ($element->getEmptyValue() !== null) {
            $elementData['emptyValue'] = \array_keys($element->getEmptyValue())[0];
        }

        return $elementData;
    }
}
