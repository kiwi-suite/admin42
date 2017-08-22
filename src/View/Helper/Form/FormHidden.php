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

class FormHidden extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);
        $elementData['staticControl'] = $element->getStaticControl();
        $elementData['staticControlText'] = "";
        if ($elementData['staticControl'] === true) {
            $translateHelper = $this->getView()->plugin('translate');
            $elementData['staticControlText'] = $translateHelper($element->getStaticControlText(), 'admin');
        }


        return $elementData;
    }
}
