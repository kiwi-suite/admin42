<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Controller;

use Admin42\Command\User\CreateCommand;
use Admin42\Command\User\DeleteCommand;
use Admin42\Command\User\EditCommand;
use Admin42\Command\User\LoginCommand;
use Admin42\Command\User\LogoutCommand;
use Admin42\Command\User\LostPasswordCommand;
use Admin42\Command\User\ManageCommand;
use Admin42\Command\User\RecoverPasswordCommand;
use Admin42\Form\User\CreateEditForm;
use Admin42\Form\User\LostPasswordForm;
use Admin42\Form\User\ManageForm;
use Admin42\Form\User\RecoverPasswordForm;
use Admin42\Model\User;
use Admin42\Mvc\Controller\AbstractAdminController;
use Admin42\Selector\SmartTable\UserSelector;
use Admin42\TableGateway\UserTableGateway;
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
            return $this->getSelector(UserSelector::class)->getResult();
        }
    }

    public function permissionDeniedAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            return new JsonModel(['error' => 'Permission denied']);
        }
    }

    /**
     * @return \Zend\Http\Response
     */
    public function homeAction()
    {
        if ($this->hasIdentity()) {
            $identityRole = $this->getPermissionService()->getRole($this->getIdentity()->getRole());

            $roleOptions = $identityRole->getOptions();

            if (empty($roleOptions['redirect_after_login'])) {
                return $this->redirect()->toRoute('admin/user/manage');
            }

            return $this->redirect()->toRoute($roleOptions['redirect_after_login']);
        }

        return $this->redirect()->toRoute('admin/user/manage');
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

        $createEditForm = $this->getForm(CreateEditForm::class);

        if ($prg !== false) {
            if ($isEditMode === true) {
                $cmd = $this->getCommand(EditCommand::class);
                $cmd->setUserId($this->params()->fromRoute("id"));
            } else {
                $cmd = $this->getCommand(CreateCommand::class);
            }

            $formCommand = $this->getFormCommand();
            $user = $formCommand->setForm($createEditForm)
                            ->setProtectedData(['password', 'status'])
                            ->setCommand($cmd)
                            ->setData($prg)
                            ->run();
            if (!$formCommand->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage([
                    'title' => 'toaster.user.detail.title.success',
                    'message' => 'toaster.user.detail.message.success',
                ]);

                return $this->redirect()->toRoute('admin/user/edit', ['id' => $user->getId()]);
            } else {
                $this->flashMessenger()->addErrorMessage([
                    'title' => 'toaster.user.detail.title.error',
                    'message' => 'toaster.user.detail.message.error',
                ]);
            }
        } else {
            if ($isEditMode === true) {
                $user = $this
                    ->getTableGateway(UserTableGateway::class)
                    ->selectByPrimary((int) $this->params()->fromRoute("id"));

                if (empty($user) || $user->getStatus() == User::STATUS_INACTIVE) {
                    return $this->redirect()->toRoute('admin/user');
                }
                $createEditForm->setData([
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'displayName' => $user->getDisplayName(),
                    'role' => $user->getRole(),
                    'shortName' => $user->getShortName(),
                ]);
            }
        }

        return [
            'createEditForm' => $createEditForm,
        ];
    }

    /**
     * @return JsonModel
     */
    public function deleteAction()
    {
        if ($this->getRequest()->isDelete()) {
            $deleteCmd = $this->getCommand(DeleteCommand::class);

            $deleteParams = [];
            parse_str($this->getRequest()->getContent(), $deleteParams);

            $deleteCmd->setUserId((int) $deleteParams['id'])
                ->run();

            return new JsonModel([
                'success' => true,
            ]);
        } elseif ($this->getRequest()->isPost()) {
            $deleteCmd = $this->getCommand(DeleteCommand::class);

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
        if ($this->hasIdentity()) {
            $identityRole = $this->getPermissionService()->getRole($this->getIdentity()->getRole());

            $roleOptions = $identityRole->getOptions();

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
                ->setCommand($this->getCommand(LoginCommand::class))
                ->run();

            if (!$formCmd->hasErrors()) {
                if ($this->params()->fromQuery('redirectTo', null) !== null) {
                    return $this->redirect()->toUrl($this->params()->fromQuery('redirectTo'));
                } else {
                    $identityRole = $this->getPermissionService()->getRole($this->getIdentity()->getRole());

                    $roleOptions = $identityRole->getOptions();

                    if (empty($roleOptions['redirect_after_login'])) {
                        return $this->redirect()->toRoute('admin/user/manage');
                    }

                    return $this->redirect()->toRoute($roleOptions['redirect_after_login']);
                }
            }
        }

        return [
            'loginForm' => $loginForm,
        ];
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function logoutAction()
    {
        $this->getCommand(LogoutCommand::class)->run();

        return $this->redirect()->toRoute('admin/login');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function lostPasswordAction()
    {
        if ($this->hasIdentity()) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

        $this->layout('admin/layout/layout-min');

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        $lostPasswordForm = $this->getForm(LostPasswordForm::class);

        if ($prg !== false) {
            $lostPasswordCommand = $this->getCommand(LostPasswordCommand::class);

            $formCmd = $this->getFormCommand();
            $formCmd->setForm($lostPasswordForm)
                    ->setCommand($lostPasswordCommand)
                    ->setData($prg)
                    ->run();
        }

        return [
            'lostPasswordForm' => $lostPasswordForm,
        ];
    }

    /**
     * @return array|\Zend\Http\Response
     * @throws \Exception
     */
    public function recoverPasswordAction()
    {
        if ($this->hasIdentity()) {
            return $this->redirect()->toRoute('admin/user/manage');
        }

        $this->layout('admin/layout/layout-min');

        $recoverPasswordForm = $this->getForm(RecoverPasswordForm::class);

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        if ($prg !== false) {
            $recoverPassowordCommand = $this->getCommand(RecoverPasswordCommand::class);
            $recoverPassowordCommand->setEmail(urldecode($this->params()->fromRoute('email')));
            $recoverPassowordCommand->setHash($this->params()->fromRoute("hash"));

            $formCmd = $this->getFormCommand();
            $formCmd->setForm($recoverPasswordForm)
                ->setCommand($recoverPassowordCommand)
                ->setData($prg)
                ->setProtectedData(['email', 'hash'])
                ->run();

            if (!$formCmd->hasErrors()) {
                return $this->redirect()->toRoute("admin/login");
            }
        }

        return [
            'recoverPasswordForm' => $recoverPasswordForm,
        ];
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

        $manageForm = $this->getForm(ManageForm::class);

        if ($prg !== false) {
            $manageCommand = $this->getCommand(ManageCommand::class);
            $manageCommand->setUser($this->getIdentity());

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
            $manageForm->setData([
                'username' => $this->getIdentity()->getUsername(),
                'email' => $this->getIdentity()->getEmail(),
                'shortName' => $this->getIdentity()->getShortName(),
                'displayName' => $this->getIdentity()->getDisplayName()
            ]);
        }

        return [
            'manageForm' => $manageForm,
        ];
    }
}
