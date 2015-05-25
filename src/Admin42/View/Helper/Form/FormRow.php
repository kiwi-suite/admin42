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

class FormRow extends \Zend\Form\View\Helper\FormRow
{
    /**
     * @param ElementInterface $element
     * @param null $labelPosition
     * @return string
     */
    public function render(ElementInterface $element, $labelPosition = null)
    {
        if ($element->getAttribute("type") === 'hidden') {
            return parent::render($element);
        }

        $elementHelper       = $this->getElementHelper();

        if ($this->partial) {
            $label = $element->getLabel();

            if (!empty($label) && '' !== $label) {
                // Translate the label
                if (null !== ($translator = $this->getTranslator())) {
                    $label = $translator->translate($label, 'admin');
                }
            }

            return $this->view->render($this->partial, [
                'element' => $element,
                'label' => $label,
                'renderErrors' => $this->renderErrors,
            ]);
        }

        return $elementHelper->renderElement($element) . PHP_EOL;
    }
}
