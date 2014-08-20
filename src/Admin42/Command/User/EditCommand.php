<?php
namespace Admin42\Command\User;

use Admin42\Model\User;
use Core42\Command\AbstractCommand;
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

    public function hydrate(array $values)
    {
        $this->setUsername(array_key_exists('username', $values) ? $values['username'] : null);
        $this->setEmail(array_key_exists('email', $values) ? $values['email'] : null);
        $this->setDisplayName(array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(array_key_exists('role', $values) ? $values['role'] : null);
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

        if (!empty($this->username)) {
            if ($emailValidator->isValid($this->username)) {
                $this->addError("username", "Username can't be an email");
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
                ->setUpdated($dateTime);


        $this->getServiceManager()->get('TableGateway')->get('Admin42\User')->update($this->user);

        return $this->user;
    }
}

