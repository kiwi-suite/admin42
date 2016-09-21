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
     *
     * @return FormElement
     */
    protected function getElementHelper()
    {
        return $this->elementHelper = $this->getView()->plugin('formElement');
    }
}
