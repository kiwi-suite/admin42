<?php
namespace Admin42\Controller;

use Admin42\Form\User\LoginForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    public function dashboardAction()
    {
        /** @var $authService \Core42\Authentication\Authentication */
        $authService = $this->getServiceLocator()->get('Core42\\Authentication');

        if (!$authService->hasIdentity()) {
            return $this->redirect()->toRoute('admin/login');
        }
    }

    public function loginAction()
    {
        /** @var $authService \Core42\Authentication\Authentication */
        $authService = $this->getServiceLocator()->get('Core42\Authentication');

        if ($authService->hasIdentity()) {
            return $this->redirect()->toRoute('admin');
        }

        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());

            /** @var $cmd \Admin42\Command\User\LoginCommand */
            $cmd = $this->getServiceLocator()->get('Command')->get('Admin42\User\Login');
            $cmd->setForm($form)
                ->run();

            if (!$cmd->hasErrors()) {
                return $this->redirect()->toRoute('admin');
            }
        }

        return new ViewModel(array(
            'loginForm' => $form,
        ));
    }

    public function logoutAction()
    {
        /** @var $authService \Core42\Authentication\Authentication */
        $authService = $this->getServiceLocator()->get('Core42\Authentication');

        $authService->clearIdentity();

        return $this->redirect()->toRoute('admin');
    }

    public function manageAction()
    {
    }
}
