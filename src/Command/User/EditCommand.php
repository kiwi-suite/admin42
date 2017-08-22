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
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Validator\EmailAddress;

class EditCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $username;

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
     * @var User
     */
    protected $user;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var string
     */
    protected $locale = "en-US";

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
     * @param string $shortName
     * @return $this
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

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
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @param array $payload
     * @return $this
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setUsername(\array_key_exists('username', $values) ? $values['username'] : null);
        $this->setEmail(\array_key_exists('email', $values) ? $values['email'] : null);
        $this->setDisplayName(\array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(\array_key_exists('role', $values) ? $values['role'] : null);
        $this->setShortName(\array_key_exists('shortName', $values) ? $values['shortName'] : null);
        $this->setPayload(\array_key_exists('payload', $values) ? $values['payload'] : []);
        $this->setLocale(\array_key_exists('locale', $values) ? $values['locale'] : 'en-US');
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

            return;
        }

        $this->username = (empty($this->username)) ? null : $this->username;
        $this->displayName = (empty($this->displayName)) ? null : $this->displayName;

        if (empty($this->email)) {
            $this->addError('email', "email can't be empty");
        }

        if (empty($this->role)) {
            $this->addError('role', 'invalid role');
        }

        $emailValidator = new EmailAddress();

        if (!$emailValidator->isValid($this->email)) {
            $this->addError('email', 'invalid email address');
        }

        $userSelect = function (Select $select) {
            $select->where(function (Where $where) {
                $where->equalTo('email', $this->email);
                $where->notEqualTo('id', $this->user->getId());
            });
        };

        if ($this->getTableGateway(UserTableGateway::class)->select($userSelect)->count() > 0) {
            $this->addError('email', 'Email already taken');
        }

        if (!empty($this->username)) {
            if ($emailValidator->isValid($this->username)) {
                $this->addError('username', "Username can't be an email");
            }
        }

        if (empty($this->shortName)) {
            $this->shortName = \mb_strtoupper(\mb_substr($this->email, 0, 1));
            if (!empty($this->displayName)) {
                $parts = \explode(' ', $this->displayName);
                $this->shortName = \mb_strtoupper($parts[0]);
                if (\count($parts) > 1) {
                    $this->shortName .= $parts[1];
                }
            }
        }
    }

    /**
     * @return User
     */
    protected function execute()
    {
        $dateTime = new DateTime();

        $this->user->setUsername($this->username)
                ->setEmail($this->email)
                ->setDisplayName($this->displayName)
                ->setRole($this->role)
                ->setShortName($this->shortName)
                ->setPayload($this->payload)
                ->setLocale($this->locale)
                ->setUpdated($dateTime);


        $this->getTableGateway(UserTableGateway::class)->update($this->user);

        return $this->user;
    }
}
