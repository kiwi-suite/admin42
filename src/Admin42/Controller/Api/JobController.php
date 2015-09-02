<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Controller\Api;

use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\View\Model\JsonModel;

class JobController extends AbstractAdminController
{
    public function runAction()
    {
        if (!$this->getRequest()->isPost()) {
            return new JsonModel();
        }
        $jobAuth = $this->getServiceLocator()->get('config')['admin']['job_auth'];
        if (empty($jobAuth)) {
            return new JsonModel();
        }


        $requestJobAuth = $this->params()->fromPost("jobAuth", null);
        if ($requestJobAuth != $jobAuth) {
            return new JsonModel();
        }

        $cmd = $this->getCommand($this->params()->fromPost("cmd"));
        if (method_exists($cmd, 'hydrate')) {
            $params = $this->params()->fromPost();
            unset($params['jobAuth']);
            unset($params['cmd']);
            try {
                $cmd->hydrate($params);
            } catch (\Exception $e) {}

        }

        $cmd->run();


        return new JsonModel();
    }
}
