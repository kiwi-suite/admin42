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
            $id = key($element->getEmptyValue());
            $valueOptions[] = [
                'id' => $id,
                'label' => $translateHelper($element->getEmptyValue()[$id], 'admin')
            ];
        }
        foreach ($element->getValueOptions() as $id => $value) {
            $valueOptions[] = [
                'id'    => $id,
                'label' => $translateHelper($value, 'admin')
            ];
        }
        $elementData['valueOptions'] = $valueOptions;
        $elementData['emptyValue'] = null;
        if ($element->getEmptyValue() !== null) {
            $elementData['emptyValue'] = array_values($element->getEmptyValue())[0];
        }

        return $elementData;
    }
}
