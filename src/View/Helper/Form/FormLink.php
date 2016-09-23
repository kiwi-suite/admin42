<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;
use Zend\Form\ElementInterface;

class FormLink extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $elementData = parent::getElementData($element, $angularNameRendering);

        $translateHelper = $this->getView()->plugin('translate');
        $urlHelper = $this->getView()->plugin('urk');

        $availableLinkTypes = [];
        foreach($element->getLinkTypes() as $type) {
            $availableLinkTypes[$type] = $translateHelper('link-type.'.$type, 'admin');
        }
        $elementData['availableLinkTypes'] = $availableLinkTypes;
        $elementData['saveUrl'] = $urlHelper('admin/link');

        return $elementData;
    }

    /**
     * @param AngularAwareInterface $element
     * @return array
     */
    public function getValue(AngularAwareInterface $element)
    {
        $value = [
            'linkId' => null,
            'linkType' => null,
            'linkValue' => null,
            'linkDisplayName' => null,
            'previewUrl' => null,
        ];
        $linkId = (int) $element->getValue();
        if ($linkId > 0) {
            $linkHelper = $this->getView()->plugin('link');
            $linkModel = $linkHelper->getLink($linkId);
            if ($linkModel) {
                $value['linkId'] = $linkModel->getId();
                $value['linkType'] = $linkModel->getType();
                $value['linkValue'] = $linkModel->getValue();
                $value['previewUrl'] = $linkHelper($linkModel->getId());
                $value['linkDisplayName'] = $linkHelper->getDisplayName($linkId);
            }
        }

        return $value;
    }

    /**
     * @param AngularAwareInterface $element
     */
    public function addElementTemplate(AngularAwareInterface $element)
    {
        parent::addElementTemplate($element);
        $this
            ->getAngularHelper()
            ->addHtmlPartial(
                'element/form/link-modal.html',
                'partial/admin42/form/link-modal'
            );

        foreach($element->getLinkTypes() as $type) {
            $this->getAngularHelper()->addHtmlPartial('link/'.$type.'.html', 'link/' . $type);
        }
    }
}
