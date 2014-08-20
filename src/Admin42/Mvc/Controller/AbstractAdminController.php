<?php
namespace Admin42\Mvc\Controller;

use Core42\Mvc\Controller\AbstractActionController;

abstract class AbstractAdminController extends AbstractActionController
{


    protected function addSidebar($forwardController, array $params = array())
    {
        $this->layout()->useSidebar = true;

        $params = array_merge($this->params()->fromRoute(), $params);

        $this->layout()->addChild(
            $this->forward()->dispatch($forwardController, $params),
            'sidebar'
        );
    }
}
