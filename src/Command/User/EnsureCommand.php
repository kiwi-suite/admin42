<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\Command\User;

use Admin42\TableGateway\UserTableGateway;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use ZF\Console\Route;

class EnsureCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $role;

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setUsername(\array_key_exists('username', $values) ? $values['username'] : null);
        $this->setPassword(\array_key_exists('password', $values) ? $values['password'] : null);
        $this->setEmail(\array_key_exists('email', $values) ? $values['email'] : null);
        $this->setStatus(\array_key_exists('status', $values) ? $values['status'] : null);
        $this->setDisplayName(\array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(\array_key_exists('role', $values) ? $values['role'] : null);
    }

    /**
     * @return User
     */
    protected function execute()
    {
        $result = $this->getTableGateway(UserTableGateway::class)->select([
            'email' => $this->email,
        ]);

        if ($result->count() > 0) {
            $user = $result->current();
            if (!empty($this->username)) {
                $user->setUsername($this->username);
            }
            if (!empty($this->status)) {
                $user->setStatus($this->status);
            }
            if (!empty($this->displayName)) {
                $user->setDisplayName($this->displayName);
            }
            if (!empty($this->role)) {
                $user->setRole($this->role);
            }

            $this->getTableGateway(UserTableGateway::class)->update($user);
        } else {
            $cmd = $this->getCommand(CreateCommand::class);
            $cmd->hydrate([
                'username' => $this->username,
                'password' => $this->password,
                'email' => $this->email,
                'status' => $this->status,
                'displayName' => $this->displayName,
                'role' => $this->role,
            ]);
            $cmd->setEnableEmail(false);
            $user = $cmd->run();
            if ($cmd->hasErrors()) {
                foreach ($cmd->getErrors() as $error => $message) {
                    $this->addError($error, $message);
                }
                return;
            }
        }

        $cmd = $this->getCommand(RecoverPasswordCommand::class);
        $cmd->setEmail($user->getEmail())
            ->setPassword($this->password)
            ->setDisableHashValidation(true)
            ->run();

        return $user;
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->hydrate([
            'username' => $route->getMatchedParam('username'),
            'password' => $route->getMatchedParam('password'),
            'email' => $route->getMatchedParam('email'),
            'status' => $route->getMatchedParam('status'),
            'displayName' => $route->getMatchedParam('displayName'),
            'role' => $route->getMatchedParam('role'),
        ]);
    }
}
