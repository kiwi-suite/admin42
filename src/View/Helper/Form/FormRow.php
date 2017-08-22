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
