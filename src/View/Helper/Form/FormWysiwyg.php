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

class FormWysiwyg extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);
        $elementData['editorOptions'] = $element->getEditorOptions();

        $urlHelper = $this->getView()->plugin('url');
        $elementData['editorOptions']['link_url'] = $urlHelper('admin/link/wysiwyg');
        $elementData['editorOptions']['link_save_url'] = $urlHelper('admin/link');

        return $elementData;
    }
}
