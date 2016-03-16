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
use Core42\Permission\Rbac\Role\RoleInterface;
use Core42\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Response;

class UserController extends AbstractAdminController
{
    /**
     * @return array|mixed
     */
    public function indexAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->getSelector('Admin42\SmartTable\User')->getResult();
        }
    }

    /**
     * @return \Zend\Http\Response
     */
    public function homeAction()
    {
        $identityRoles = $this
            ->getServiceLocator()
            ->get('Core42\Permission')
            ->getService('admin42')
            ->getIdentityRoles();

        if (empty($identityRoles)) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

        /** @var RoleInterface $role */
        $role = current($identityRoles);
        $roleOptions = $role->getOptions();

        if (empty($roleOptions['redirect_after_login'])) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

        return $this->redirect()->toRoute($roleOptions['redirect_after_login']);
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
                $this->flashMessenger()->addSuccessMessage([
                    'title' => 'toaster.user.detail.title.success',
                    'message' => 'toaster.user.detail.message.success',
                ]);

                return $this->redirect()->toRoute('admin/user/edit', array('id' => $user->getId()));
            } else {
                $this->flashMessenger()->addErrorMessage([
                    'title' => 'toaster.user.detail.title.error',
                    'message' => 'toaster.user.detail.message.error',
                ]);
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
                    'shortName' => $user->getShortName(),
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

            $this->flashMessenger()->addSuccessMessage([
                'title' => 'toaster.user.delete.title.success',
                'message' => 'toaster.user.delete.message.success',
            ]);

            return new JsonModel([
               'redirect' => $this->url()->fromRoute('admin/user')
            ]);
        }

        return new JsonModel([
            'redirect' => $this->url()->fromRoute('admin/user')
        ]);
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
            $identityRoles = $this
                ->getServiceLocator()
                ->get('Core42\Permission')
                ->getService('admin42')
                ->getIdentityRoles();

            if (empty($identityRoles)) {
                return $this->redirect()->toRoute('admin/user/manage');
            }

            /** @var RoleInterface $role */
            $role = current($identityRoles);
            $roleOptions = $role->getOptions();

            if (empty($roleOptions['redirect_after_login'])) {
                return $this->redirect()->toRoute('admin/user/manage');
            }

            return $this->redirect()->toRoute($roleOptions['redirect_after_login']);
        }

        $this->layout('admin/layout/layout-min');

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
                    $identityRoles = $this
                        ->getServiceLocator()
                        ->get('Core42\Permission')
                        ->getService('admin42')
                        ->getIdentityRoles();

                    if (empty($identityRoles)) {
                        return $this->redirect()->toRoute('admin/user/manage');
                    }

                    /** @var RoleInterface $role */
                    $role = current($identityRoles);
                    $roleOptions = $role->getOptions();

                    if (empty($roleOptions['redirect_after_login'])) {
                        return $this->redirect()->toRoute('admin/user/manage');
                    }

                    return $this->redirect()->toRoute($roleOptions['redirect_after_login']);
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

        $this->layout('admin/layout/layout-min');

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

        $this->layout('admin/layout/layout-min');

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
                $this->flashMessenger()->addSuccessMessage([
                    'title' => 'toaster.user.manage.title.success',
                    'message' => 'toaster.user.manage.message.success',
                ]);
            }
        } else {
            $manageForm->setData(array(
                'username' => $authenticationService->getIdentity()->getUsername(),
                'email' => $authenticationService->getIdentity()->getEmail(),
                'shortName' => $authenticationService->getIdentity()->getShortName(),
                'displayName' => $authenticationService->getIdentity()->getDisplayName()
            ));
        }

        return array(
            'manageForm' => $manageForm,
        );
    }
}
