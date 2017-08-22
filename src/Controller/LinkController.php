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


namespace Admin42\Controller;

use Admin42\Link\LinkProvider;
use Admin42\Model\Link;
use Admin42\Mvc\Controller\AbstractAdminController;
use Admin42\Selector\LinkSelector;
use Admin42\TableGateway\LinkTableGateway;
use Core42\View\Model\JsonModel;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Json\Json;
use Zend\View\Model\ViewModel;

class LinkController extends AbstractAdminController
{
    /**
     * @return JsonModel
     */
    public function saveAction()
    {
        $data = Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY);

        $encodedValue = Json::encode($data['value']);

        $result = $this->getTableGateway(LinkTableGateway::class)->select([
            'type' => $data['type'],
            'hash' => \md5($encodedValue),
        ]);

        if ($result->count() > 0) {
            $link = $result->current();
        } else {
            $link = new Link();
            $link->setType($data['type'])
                ->setHash(\md5($encodedValue))
                ->setValue($data['value']);

            $this->getTableGateway(LinkTableGateway::class)->insert($link);
        }

        return new JsonModel([
            'linkId' => $link->getId(),
            'linkDisplayName' => $this->getServiceManager()->get(LinkProvider::class)->getDisplayName(
                $link->getType(),
                $data['value']
            ),
            'url' => $this->getServiceManager()->get(LinkProvider::class)->assemble(
                $link->getType(),
                $data['value']
            ),
        ]);
    }

    public function wysiwygAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        /** @var LinkProvider $linkProvider */
        $linkProvider = $this->getServiceManager()->get(LinkProvider::class);
        $availableAdapters = [];
        foreach ($linkProvider->getAvailableAdapters() as $adapter) {
            $availableAdapters[$adapter] = $this
                ->getServiceManager()
                ->get(TranslatorInterface::class)->translate(
                    'link-type.' . $adapter,
                    'admin'
                );
        }
        $viewModel->setVariable('availableAdapters', $availableAdapters);

        $partialList = [];
        foreach ($linkProvider->getAvailableAdapters() as $adapterName) {
            $partialList = \array_merge($linkProvider->getAdapter($adapterName)->getPartials(), $partialList);
        }
        $viewModel->setVariable('linkPartialList', $partialList);

        $linkData = [
            'linkId' => null,
            'linkType' => null,
            'linkValue' => null,
            'linkDisplayName' => null,
        ];
        $linkId = $this->params()->fromRoute("id");
        if ($linkId > 0) {
            $linkModel = $this
                ->getServiceManager()
                ->get('Selector')
                ->get(LinkSelector::class)
                ->setLinkId($linkId)
                ->getResult();

            if ($linkModel) {
                $linkData['linkId'] = $linkModel->getId();
                $linkData['linkType'] = $linkModel->getType();
                $linkData['linkValue'] = $linkModel->getValue();
                $linkData['linkDisplayName'] = $this
                    ->getServiceManager()
                    ->get(LinkProvider::class)
                    ->getDisplayName($linkModel->getType(), $linkModel->getValue());
            }
        }

        $viewModel->setVariable("linkData", $linkData);

        return $viewModel;
    }
}
