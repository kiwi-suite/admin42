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

        if ($this->getView()->getHelperPluginManager()->has('form'.$name)) {
            return $this->renderHelper('form'.$name, $element);
        }

        return parent::render($element);
    }
}
