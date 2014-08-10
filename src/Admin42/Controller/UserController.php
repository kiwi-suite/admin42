<?php
namespace Admin42\Controller;

use Admin42\Mvc\Controller\AbstractAdminController;

class UserController extends AbstractAdminController
{
    public function dashboardAction()
    {

    }

    public function loginAction()
    {
        $loginForm = $this->getForm('Admin42\User\Login');

        if ($this->getRequest()->isPost()) {
            $formCmd = $this->getFormCommand();

            $formCmd->setForm($loginForm)
                ->setCommand($this->getCommand('Admin42\User\Login'))
                ->run();
        }

        return array(
            'loginForm' => $loginForm,
        );
    }

    public function logoutAction()
    {
        $this->getCommand('Admin42\User\Logout')->run();

        return $this->redirect()->toRoute('admin/login');
    }

    public function manageAction()
    {
        $this->layout()->useSidebar = true;
    }
}
