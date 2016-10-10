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

use Admin42\FormElements\AngularAwareInterface;
use Zend\Form\FieldsetInterface;

class FormStack extends FormHelper
{
    public function getValue(AngularAwareInterface $element)
    {
        return [];
    }

    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);

        $translateHelper = $this->getView()->plugin('translate');

        $protoTypes = [];
        /** @var FieldsetInterface $fieldset */
        foreach ($element->getProtoTypes() as $fieldset) {
            $name = (new \ReflectionClass($fieldset))->getShortName();

            if (!$this->getView()->getHelperPluginManager()->has('form' . $name)) {
                continue;
            }

            $formHelper = $this->getView()->plugin('form' . $name);
            if (!($formHelper instanceof AngularHelperInterface)) {
                continue;
            }

            $fieldset = clone $fieldset;
            $fieldset->remove('__index__');
            $fieldset->remove('__type__');
            $fieldset->remove('__name__');
            $fieldset->remove('__deleted__');

            $label = $fieldset->getLabel();
            if (!empty($label)) {
                $label = $translateHelper($label, 'admin');
            }
            $protoTypes[] = [
                'label' => $label,
                'directive' => $formHelper->getAngularDirective($fieldset),
                'elementData' => $this->getAngularHelper()->generateJsonTemplate(
                    $formHelper->getElementData($fieldset),
                    'element/form/value/'
                ),
            ];
        }
        usort($protoTypes, function ($a, $b) {
            return strcasecmp($a['label'], $b['label']);
        });

        $elementData['protoTypes'] = $protoTypes;


        $elements = [];
        foreach ($element->getIterator() as $fieldset) {
            $name = (new \ReflectionClass($fieldset))->getShortName();

            if (!$this->getView()->getHelperPluginManager()->has('form' . $name)) {
                continue;
            }
            $formHelper = $this->getView()->plugin('form' . $name);
            if (!($formHelper instanceof AngularHelperInterface)) {
                continue;
            }

            $fieldset->remove('__index__');
            $fieldset->remove('__type__');

            $value = $fieldset->get('__name__')->getValue();
            if (empty($value)) {
                $value = '';
            }
            $fieldset->setOption('fieldsetName', $value);
            $fieldset->remove('__name__');


            $value = $fieldset->get('__deleted__')->getValue();
            $value = ($value == 'true');
            $fieldset->setOption('fieldsetDeleted', $value);
            $fieldset->remove('__deleted__');

            $elements[] = [
                'directive' => $formHelper->getAngularDirective($fieldset),
                'elementDataId' => $this->getAngularHelper()->generateJsonTemplate(
                    $formHelper->getElementData($fieldset),
                    'element/form/value/'
                ),
            ];
        }


        $elementData['elements'] = $elements;

        return $elementData;
    }
}
