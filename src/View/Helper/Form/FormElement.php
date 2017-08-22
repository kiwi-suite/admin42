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

class FormElement extends \Zend\Form\View\Helper\FormElement
{
    /**
     * @param ElementInterface|null $element
     * @return $this|string
     */
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
        $name = (new \ReflectionClass($element))->getShortName();

        if ($this->getView()->getHelperPluginManager()->has('form' . $name)) {
            return $this->renderHelper('form' . $name, $element);
        }

        return parent::render($element);
    }
}
