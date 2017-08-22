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

use Admin42\Model\User;
use Admin42\TableGateway\UserTableGateway;
use Core42\Command\AbstractCommand;
use Core42\Stdlib\DateTime;

class DeleteCommand extends AbstractCommand
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $userId;

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!empty($this->userId)) {
            $this->user = $this->getTableGateway(UserTableGateway::class)->selectByPrimary((int) $this->userId);
        }

        if (!($this->user instanceof User)) {
            $this->addError('user', 'invalid user');
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $dateTime = new DateTime();
        $this->user->setStatus(User::STATUS_INACTIVE)
            ->setEmail(\json_encode([
                $this->user->getEmail(),
                $dateTime->format('Y-m-d H:i:s'),
            ]))
            ->setUsername(\json_encode([
                $this->user->getUsername(),
                $dateTime->format('Y-m-d H:i:s'),
            ]))
            ->setUpdated($dateTime);
        $this->getTableGateway(UserTableGateway::class)->update($this->user);
    }
}
