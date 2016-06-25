<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\User;

use Admin42\Model\User;
use Core42\Command\AbstractCommand;

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
            $this->user = $this->getTableGateway('Admin42\User')->selectByPrimary((int) $this->userId);
        }

        if (!($this->user instanceof User)) {
            $this->addError("user", "invalid user");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $dateTime = new \DateTime();
        $this->user->setStatus(User::STATUS_INACTIVE)
            ->setEmail(json_encode([
                $this->user->getEmail(),
                $dateTime->format('Y-m-d H:i:s')
            ]))
            ->setUsername(json_encode([
                $this->user->getUsername(),
                $dateTime->format('Y-m-d H:i:s')
            ]))
            ->setUpdated($dateTime);
        $this->getTableGateway('Admin42\User')->update($this->user);
    }
}
