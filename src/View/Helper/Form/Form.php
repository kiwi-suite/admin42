<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */
namespace Admin42\View\Helper\Form;

use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;

class Form extends \Zend\Form\View\Helper\Form
{

    /**
     * Render a form from the provided $form,
     *
     * @param  FormInterface $form
     * @return string
     */
    public function render(FormInterface $form)
    {
        if (method_exists($form, 'prepare')) {
            $form->prepare();
        }

        $formContent = '';

        foreach ($form as $element) {
            if ($element instanceof FieldsetInterface) {
                $formContent.= $this->getView()->formCollection($element);
            } else {
                $formContent.= $this->getView()->formRow($element);
            }
        }

        return $formContent;
    }

    /**
     * Render a form from the provided $form,
     *
     * @param  FormInterface $form
     * @return string
     */
    public function renderWithFormTag(FormInterface $form)
    {
        return parent::render($form);
    }
}
