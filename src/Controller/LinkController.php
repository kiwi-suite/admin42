<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Controller;

use Admin42\Link\LinkProvider;
use Admin42\Model\Link;
use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\View\Model\JsonModel;
use Zend\Json\Json;

class LinkController extends AbstractAdminController
{
    /**
     * @return JsonModel
     */
    public function saveAction()
    {
        $data = Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY);

        $encodedValue = Json::encode($data['value']);

        $result = $this->getTableGateway('Admin42\Link')->select([
            'type' => $data['type'],
            'hash' => md5($encodedValue),
        ]);

        if ($result->count() > 0) {
            $link = $result->current();
        } else {
            $link = new Link();
            $link->setType($data['type'])
                ->setHash(md5($encodedValue))
                ->setValue($encodedValue);

            $this->getTableGateway('Admin42\Link')->insert($link);
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
}
