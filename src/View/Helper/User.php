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


namespace Admin42\View\Helper;

use Admin42\Model\User as UserModel;
use Admin42\TableGateway\UserTableGateway;
use Zend\View\Helper\AbstractHelper;

class User extends AbstractHelper
{
    /**
     * @var UserTableGateway
     */
    private $userTableGateway;

    /**
     * @var array
     */
    private $userCache = [];

    /**
     * @param UserTableGateway $userTableGateway
     */
    public function __construct(
        UserTableGateway $userTableGateway
    ) {
        $this->userTableGateway = $userTableGateway;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param int|UserModel $userId
     * @return User
     */
    public function getUser($userId)
    {
        if ($userId instanceof UserModel) {
            $this->userCache[$userId->getId()] = $userId;
            $userId = $this->userCache[$userId->getId()]->getId();
        }

        $userId = (int) $userId;
        if (!\array_key_exists($userId, $this->userCache)) {
            $user = $this->userTableGateway->selectByPrimary($userId);
            if (empty($user)) {
                $user = new UserModel();
            }

            $this->userCache[$user->getId()] = $user;
        }

        return $this->userCache[$userId];
    }

    /**
     * @param int|UserModel $userId
     * @return string
     */
    public function getDisplayName($userId)
    {
        $user = $this->getUser($userId);
        $displayName = $user->getDisplayName();
        if (empty($displayName)) {
            $displayName = $user->getUsername();
        }
        if (empty($displayName)) {
            $displayName = $user->getEmail();
        }

        return $displayName;
    }

    /**
     * @param $method
     * @param $attributes
     * @return mixed
     */
    public function __call($method, $attributes)
    {
        $userId = $attributes[0];
        $user = $this->getUser($userId);

        return \call_user_func([$user, $method]);
    }
}
