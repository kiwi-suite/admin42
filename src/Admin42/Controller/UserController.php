<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Controller;

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\User;
use Admin42\Mvc\Controller\AbstractAdminController;
use Core42\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Response;

class UserController extends AbstractAdminController
{
    /**
     * @return array|mixed
     */
    public function indexAction()
    {
        $list = $this->getSelector('Admin42\DataTable\UserList');
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $list->getResult();
        }

        return array(
            'dataTable' => $list->getDataTable(),
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
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
            $user = $formCommand->setForm($createEditForm)
                            ->setProtectedData(array('password', 'status'))
                            ->setCommand($cmd)
                            ->setData($prg)
                            ->run();
            if (!$formCommand->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage("Success");

                return $this->redirect()->toRoute('admin/user/edit', array('id' => $user->getId()));
            } else {
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

        return array(
            'createEditForm' => $createEditForm,
        );
    }

    /**
     * @return \Zend\Http\Response
     */
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

    /**
     *
     */
    public function dashboardAction()
    {
        return $this->redirect()->toRoute('admin/login');
    }

    /**
     * @return array|\Zend\Http\Response
     * @throws \Exception
     */
    public function loginAction()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceLocator()->get('Admin42\Authentication');
        if ($authenticationService->hasIdentity()) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

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

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function logoutAction()
    {
        $this->getCommand('Admin42\User\Logout')->run();

        return $this->redirect()->toRoute('admin/login');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function lostPasswordAction()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceLocator()->get('Admin42\Authentication');
        if ($authenticationService->hasIdentity()) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        $lostPasswordForm = $this->getForm('Admin42\User\LostPassword');

        if ($prg !== false) {
            $lostPasswordCommand = $this->getCommand('Admin42\User\LostPassword');

            $formCmd = $this->getFormCommand();
            $formCmd->setForm($lostPasswordForm)
                    ->setCommand($lostPasswordCommand)
                    ->setData($prg)
                    ->run();
        }

        return array(
            'lostPasswordForm' => $lostPasswordForm,
        );
    }

    /**
     * @return array|\Zend\Http\Response
     * @throws \Exception
     */
    public function recoverPasswordAction()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getServiceLocator()->get('Admin42\Authentication');
        if ($authenticationService->hasIdentity()) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

        $recoverPasswordForm = $this->getForm('Admin42\User\RecoverPassword');

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        if ($prg !== false) {
            $recoverPassowordCommand = $this->getCommand('Admin42\User\RecoverPassword');
            $recoverPassowordCommand->setEmail(urldecode($this->params()->fromRoute('email')));
            $recoverPassowordCommand->setHash($this->params()->fromRoute("hash"));

            $formCmd = $this->getFormCommand();
            $formCmd->setForm($recoverPasswordForm)
                ->setCommand($recoverPassowordCommand)
                ->setData($prg)
                ->setProtectedData(array('email', 'hash'))
                ->run();

            if (!$formCmd->hasErrors()) {
                return $this->redirect()->toRoute("admin/login");
            }
        }

        return array(
            'recoverPasswordForm' => $recoverPasswordForm,
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
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
