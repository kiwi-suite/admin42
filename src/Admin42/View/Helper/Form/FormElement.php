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
    public function renderElement(ElementInterface $element)
    {
        $type = $this->getType($element);
        if ($type === null) {
            return $this->render($element);
        }

        $resolved = $this->getView()->resolver('form/'.$type);
        if ($resolved === false) {
            return $this->render($element);
        }

        $partialHelper = $this->view->plugin('partial');
        return $partialHelper('form/'.$type, [
            'element'       => $element,
            'hasErrors'     => count($element->getMessages()) > 0
        ]);
    }

    /**
     *
     * @param ElementInterface $element
     * @return string|null
     */
    protected function getType(ElementInterface $element)
    {
        foreach ($this->classMap as $class => $pluginName) {
            if ($element instanceof $class) {
                return substr($pluginName, 4);
            }
        }

        $type = $element->getAttribute('type');

        if (isset($this->typeMap[$type])) {
            return substr($this->typeMap[$type], 4);
        }
        return null;
    }
}
