<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */
namespace Admin42\View\Helper\Form;

use Admin42\FormElements\Dynamic;
use Zend\Form\ElementInterface;
use Zend\Form\FieldsetInterface;
use Zend\Form\Element\Collection as CollectionElement;

class FormCollection extends \Zend\Form\View\Helper\FormCollection
{
    /**
     * Render a collection by iterating through all fieldsets and elements
     *
     * @param  ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        $elements           = [];
        $templateProtoTypes   = [];
        $elementHelper    = $this->getElementHelper();
        $fieldsetHelper   = $this->getFieldsetHelper();

        if ($element instanceof CollectionElement && $element->shouldCreateTemplate()) {
            $templateProtoTypes[] = $this->renderCollectionTemplate($element);
        } elseif ($element instanceof Dynamic) {
            $templateProtoTypes = $this->renderDynamicTemplate($element);
        }

        foreach ($element->getIterator() as $elementOrFieldset) {
            if ($elementOrFieldset instanceof FieldsetInterface) {
                $elements[] = [
                    'markup' => $fieldsetHelper($elementOrFieldset, $this->shouldWrap()),
                    'element' => $elementOrFieldset
                ];
            } elseif ($elementOrFieldset instanceof ElementInterface) {
                $elements[] = [
                    'markup' => $elementHelper($elementOrFieldset),
                    'element' => $elementOrFieldset
                ];
            }
        }

        $parts = explode("\\", get_class($element));
        $partialHelper = $this->view->plugin('partial');
        return $partialHelper('form/' . strtolower(end($parts)), [
            'elements' => $elements,
            'legend' => $element->getLabel(),
            'templateProtoTypes' => $templateProtoTypes,
            'element' => $element,
        ]);
    }

    /**
     * Only render a template
     *
     * @param  CollectionElement $collection
     * @return string
     */
    public function renderCollectionTemplate(CollectionElement $collection)
    {
        $elementHelper          = $this->getElementHelper();
        $fieldsetHelper         = $this->getFieldsetHelper();

        $templateMarkup         = '';

        $elementOrFieldset = $collection->getTemplateElement();

        if ($elementOrFieldset instanceof FieldsetInterface) {
            $templateMarkup .= $fieldsetHelper($elementOrFieldset, $this->shouldWrap());
        } elseif ($elementOrFieldset instanceof ElementInterface) {
            $templateMarkup .= $elementHelper($elementOrFieldset);
        }

        return [
            'markup' => $templateMarkup,
            'element' => $elementOrFieldset,
        ];
    }

    /**
     * @param Dynamic $dynamic
     * @return array
     */
    public function renderDynamicTemplate(Dynamic $dynamic)
    {
        $elementHelper          = $this->getElementHelper();
        $fieldsetHelper         = $this->getFieldsetHelper();

        $elementOrFieldsetArray = $dynamic->getTemplateElements();

        $return = [];

        foreach ($elementOrFieldsetArray as $elementOrFieldset) {
            $templateMarkup         = '';

            if ($elementOrFieldset instanceof FieldsetInterface) {
                $templateMarkup .= $fieldsetHelper($elementOrFieldset, $this->shouldWrap());
            } elseif ($elementOrFieldset instanceof ElementInterface) {
                $templateMarkup .= $elementHelper($elementOrFieldset);
            }

            $return[] = [
                'markup' => $templateMarkup,
                'element' => $elementOrFieldset,
                'templatePlaceholder' => $dynamic->getTemplatePlaceholder()
            ];
        }

        return $return;
    }
}
