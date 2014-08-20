<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Mvc\Controller;

use Core42\Mvc\Controller\AbstractActionController;

abstract class AbstractAdminController extends AbstractActionController
{
    /**
     * @param string $forwardController
     * @param array $params
     */
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
