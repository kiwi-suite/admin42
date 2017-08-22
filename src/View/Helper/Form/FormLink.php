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

use Admin42\FormElements\AngularAwareInterface;

class FormLink extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $this
            ->getAngularHelper()
            ->addHtmlPartial(
                'element/form/link-modal.html',
                'partial/admin42/form/link-modal'
            );

        $linkTypes = $element->getLinkTypes();
        if ($linkTypes === null) {
            $linkTypes = $element->getAllLinkTypes();
        }

        foreach ($linkTypes as $type) {
            foreach ($element->getLinkPartial($type) as $angularName => $partialName) {
                $this->getAngularHelper()->addHtmlPartial($angularName, $partialName);
            }
        }

        $elementData = parent::getElementData($element, $angularNameRendering);

        $translateHelper = $this->getView()->plugin('translate');
        $urlHelper = $this->getView()->plugin('url');

        $availableLinkTypes = [];
        foreach ($linkTypes as $type) {
            $availableLinkTypes[$type] = $translateHelper('link-type.' . $type, 'admin');
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
}
