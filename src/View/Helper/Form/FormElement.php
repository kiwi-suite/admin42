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
