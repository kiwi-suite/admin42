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

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;
use Zend\View\Helper\AbstractHelper;

class FormRow extends AbstractHelper
{
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        return $this->getElementHelper()->render($element) . PHP_EOL;
    }

    /**
     * @return FormElement
     */
    protected function getElementHelper()
    {
        return $this->elementHelper = $this->getView()->plugin('formElement');
    }
}
