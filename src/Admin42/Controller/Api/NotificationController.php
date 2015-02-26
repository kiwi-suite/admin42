<?php
/**
* admin42 (www.raum42.at)
*
* @link http://www.raum42.at
* @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
*
*/

namespace Admin42\Controller\Api;

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\Notification;
use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\View\Model\JsonModel;
use Zend\Db\Sql\Select;

class NotificationController extends AbstractAdminController
{

    public function listAction()
    {
        /** @var AuthenticationService $authService */
        $authService = $this->getServiceLocator()->get('Admin42\Authentication');

        $notifications = array();

        $result = $this->getTableGateway('Admin42\Notification')->select(function(Select $select) use ($authService){
            $select->where(array(
                'userId' => $authService->getIdentity()->getId()
            ));
            $select->order("created DESC");
            $select->limit(15);
        });

        /** @var Notification $_notification */
        foreach ($result as $_notification) {
            $link = null;
            if (strlen($_notification->getRoute()) > 0) {
                $params = (strlen($_notification->getRouteParams()) > 0)
                    ? json_decode($_notification->getRouteParams(), true)
                    : array();
                $link = $this->url()->fromRoute($_notification->getRoute(), $params);
            }

            $notifications[] = array(
                'id' => $_notification->getId(),
                'text' => $_notification->getText(),
                'link' => $link,
                'created' => $_notification->getCreated()->format("Y-m-d H:i:s")
            );
        }

        return new JsonModel($notifications);
    }

    public function clearAction()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceLocator()->get('Admin42\Authentication');

        $this->getTableGateway('Admin42\Notification')->delete(array(
            'userId' => $authenticationService->getIdentity()->getId()
        ));

        return new JsonModel(array('success' => true));
    }
}
