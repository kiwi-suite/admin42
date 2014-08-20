<?php
namespace Admin42\Controller;

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\User;
use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;

class UserController extends AbstractAdminController
{

    public function indexAction()
    {
        $list = $this->getSelector('Admin42\DataTable\Userlist');
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $list->getResult();
        }

        return array(
            'dataTable' => $list->getDataTable(),
        );
    }

    public function indexSidebarAction()
    {
        $list = $this->getSelector('Admin42\DataTable\UserlistSidebar');
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $list->getResult();
        }

        $viewModel = new ViewModel(array(
            'dataTable' => $list->getDataTable(),
        ));

        return $viewModel;
    }

    public function detailAction()
    {
        $isEditMode = $this->params()->fromRoute("isEditMode");

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        $createEditForm = $this->getForm('Admin42\User\CreateEdit');

        if ($prg !== false) {
            if ($isEditMode === true) {
                $cmd = $this->getCommand('Admin42\User\Edit');
                $cmd->setUserId($this->params()->fromRoute("id"));
            } else {
                $cmd = $this->getCommand('Admin42\User\Create');
            }

            $formCommand = $this->getFormCommand();
            $formCommand->setForm($createEditForm)
                            ->setProtectedData(array('password', 'status'))
                            ->setCommand($cmd)
                            ->setData($prg)
                            ->run();
            if (!$formCommand->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage("Success");
            } else {
                var_dump($formCommand->getErrors());
                $this->flashMessenger()->addErrorMessage("Error");
            }
        } else {
            if ($isEditMode === true) {
                $user = $this->getTableGateway('Admin42\User')->selectByPrimary((int) $this->params()->fromRoute("id"));
                if (empty($user) || $user->getStatus() == User::STATUS_INACTIVE) {
                    return $this->redirect()->toRoute('admin/user');
                }
                $createEditForm->setData(array(
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'displayName' => $user->getDisplayName(),
                    'role' => $user->getRole(),
                ));
            }
        }

        $this->addSidebar('Admin42\User', array('action' => 'index-sidebar'));

        return array(
            'createEditForm' => $createEditForm,
        );
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isDelete()) {
            $deleteCmd = $this->getCommand('Admin42\User\Delete');

            $deleteParams = array();
            parse_str($this->getRequest()->getContent(), $deleteParams);

            $deleteCmd->setUserId((int) $deleteParams['id'])
                ->run();

            return new JsonModel(array(
                'success' => true,
            ));
        } elseif ($this->getRequest()->isPost()) {
            $deleteCmd = $this->getCommand('Admin42\User\Delete');

            $deleteCmd->setUserId((int) $this->params()->fromPost('id'))
                ->run();

            $this->flashMessenger()->addSuccessMessage("Success");
            return $this->redirect()->toRoute('admin/user');
        }

        return $this->redirect()->toRoute('admin/user');
    }

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

            if (!$formCmd->hasErrors()) {
                if ($this->params()->fromQuery('redirectTo', null) !== null) {
                    return $this->redirect()->toUrl($this->params()->fromQuery('redirectTo'));
                } else {
                    return $this->redirect()->toRoute('admin/user/manage');
                }
            }
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
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceLocator()->get('Admin42\Authentication');

        $manageForm = $this->getForm('Admin42\User\Manage');

        if ($prg !== false) {
            $manageCommand = $this->getCommand('Admin42\User\Manage');
            $manageCommand->setUser($authenticationService->getIdentity());

            $formCmd = $this->getFormCommand();
            $formCmd->setForm($manageForm)
                        ->setData($prg)
                        ->setCommand($manageCommand)
                        ->run();


            if (!$formCmd->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage("Success");
            }
        } else {

            $manageForm->setData(array(
                'username' => $authenticationService->getIdentity()->getUsername(),
                'email' => $authenticationService->getIdentity()->getEmail(),
                'displayName' => $authenticationService->getIdentity()->getDisplayName()
            ));
        }

        return array(
            'manageForm' => $manageForm,
        );
    }
}
