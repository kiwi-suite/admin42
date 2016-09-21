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

class FormMultiCheckbox extends FormHelper
{
    /**
     * @param ElementInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(ElementInterface $element, $angularNameRendering = true)
    {
        $translateHelper = $this->getView()->plugin('translate');

        $elementData = parent::getElementData($element, $angularNameRendering);

        $valueOptions = [];
        foreach ($element->getValueOptions() as $id => $value) {
            $valueOptions[] = [
                'id'    => $id,
                'label' => $translateHelper($value, 'admin')
            ];
        }
        $elementData['valueOptions'] = $valueOptions;

        return $elementData;
    }
}
