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
