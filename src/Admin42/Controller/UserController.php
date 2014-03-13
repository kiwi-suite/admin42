<?php
namespace Admin42\Controller;

use Admin42\Command\User\EditCommand;
use Core42\ValueManager\ValueManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    public function dashboardAction()
    {

    }

    public function loginAction()
    {
        /** @var $authService \Core42\Authentication\Authentication */
        $authService = $this->getServiceLocator()->get('Core42\\Authentication');

        if ($authService->hasIdentity()) {
            return $this->redirect('admin');
        }

        if ($this->getRequest()->isPost()) {
            $authService->getAdapter()
                    ->setIdentity($this->params()->fromPost("username", ""))
                    ->setCredential($this->params()->fromPost("password"), "");

            $authResult = $authService->authenticate();
            if ($authResult->isValid()) {
                return $this->redirect('admin');
            }
        }
    }

    public function manageAction()
    {
        $valueManager = new ValueManager();
        $valueManager->setValues($this->getServiceLocator()->get('Core42\\Authentication')->getIdentity()->extract());

        if ($this->getRequest()->isPost()) {
            /** @var $cmd \Admin42\Command\User\EditCommand */
            $cmd = $this->getServiceLocator()->get('Command')->get('Admin42\\User\\Edit');
            $cmd->setEmail($this->params()->fromPost("email"))
                    ->setUser($this->getServiceLocator()->get('Core42\\Authentication')->getIdentity())
                    ->run();

            if (!$cmd->hasCommandErrors()) {
                return $this->redirect('admin/user/manage');
            }
            $valueManager = $cmd->getValueManager();
        }

        return new ViewModel(array(
            'valueManager' => $valueManager,
        ));
    }
}
