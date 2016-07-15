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
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Validator\EmailAddress;

class EditCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $role;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $shortName;

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
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setUsername(array_key_exists('username', $values) ? $values['username'] : null);
        $this->setEmail(array_key_exists('email', $values) ? $values['email'] : null);
        $this->setDisplayName(array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(array_key_exists('role', $values) ? $values['role'] : null);
        $this->setShortName(array_key_exists('shortName', $values) ? $values['shortName'] : null);
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

            return;
        }

        $this->username = (empty($this->username)) ? null : $this->username;
        $this->displayName = (empty($this->displayName)) ? null : $this->displayName;

        if (empty($this->email)) {
            $this->addError("email", "email can't be empty");
        }

        if (empty($this->role)) {
            $this->addError("role", "invalid role");
        }

        $emailValidator = new EmailAddress();

        if (!$emailValidator->isValid($this->email)) {
            $this->addError("email", "invalid email address");
        }

        $userSelect = function (Select $select) {
            $select->where(function (Where $where) {
                    $where->equalTo('email', $this->email);
                    $where->notEqualTo('id', $this->user->getId());
            });
        };

        if ($this->getTableGateway('Admin42\User')->select($userSelect)->count() > 0) {
            $this->addError("email", "Email already taken");
        }

        if (!empty($this->username)) {
            if ($emailValidator->isValid($this->username)) {
                $this->addError("username", "Username can't be an email");
            }
        }

        if (empty($this->shortName)) {
            $this->shortName = strtoupper(substr($this->email, 0, 1));
            if (!empty($this->displayName)) {
                $parts = explode(" ", $this->displayName);
                $this->shortName = strtoupper($parts[0]);
                if (count($parts) > 1) {
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
        $dateTime = new \DateTime();

        $this->user->setUsername($this->username)
                ->setEmail($this->email)
                ->setDisplayName($this->displayName)
                ->setRole($this->role)
                ->setShortName($this->shortName)
                ->setUpdated($dateTime);


        $this->getTableGateway('Admin42\User')->update($this->user);

        return $this->user;
    }
}