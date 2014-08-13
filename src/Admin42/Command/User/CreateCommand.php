<?php
namespace Admin42\Command\User;

use Admin42\Model\User;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\EmailAddress;
use ZF\Console\Route;


class CreateCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $cryptedPassword;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $role;

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

    public function hydrate(array $values)
    {
        $this->setUsername(array_key_exists('username', $values) ? $values['username'] : null);
        $this->setPassword(array_key_exists('password', $values) ? $values['password'] : null);
        $this->setEmail(array_key_exists('email', $values) ? $values['email'] : null);
        $this->setStatus(array_key_exists('status', $values) ? $values['status'] : null);
        $this->setDisplayName(array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(array_key_exists('role', $values) ? $values['role'] : null);
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (empty($this->email)) {
            $this->addError("email", "email can't be empty");
        }

        if (empty($this->role)) {
            $this->addError("role", "invalid role");
        }

        if (!in_array($this->status, array(User::STATUS_INACTIVE, User::STATUS_ACTIVE))) {
            $this->addError("status", "invalid status '{$this->status}'");
        }

        $this->username = (empty($this->username)) ? null : $this->username;
        $this->displayName = (empty($this->displayName)) ? null : $this->displayName;

        if (!empty($this->username)) {
            $emailValidator = new EmailAddress();
            if ($emailValidator->isValid($this->username)) {
                $this->addError("username", "Username can't be an email");
            }
        }

        if (empty($this->password)) {
            $set = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789!@#$%&*?';
            for ($i = 0; $i < 12; $i++) {
                $this->password .= array_rand(str_split($set));
            }
        }

        $bCrypt = new Bcrypt();
        $this->cryptedPassword = $bCrypt->create($this->password);
    }

    /**
     *
     */
    protected function execute()
    {
        $dateTime = new \DateTime();

        $user = new User();
        $user->setUsername($this->username)
                ->setPassword($this->cryptedPassword)
                ->setEmail($this->email)
                ->setDisplayName($this->displayName)
                ->setRole($this->role)
                ->setStatus($this->status)
                ->setUpdated($dateTime)
                ->setCreated($dateTime);


        $this->getServiceManager()->get('TableGateway')->get('Admin42\User')->insert($user);

        $this->consoleOutput("<info>User {$this->email} created</info>");
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        $this->hydrate(array(
            'username' => $route->getMatchedParam("username"),
            'password' => $route->getMatchedParam("password"),
            'email' => $route->getMatchedParam("email"),
            'status' => $route->getMatchedParam("status"),
            'displayName' => $route->getMatchedParam("displayName"),
            'role' => $route->getMatchedParam("role"),
        ));
    }
}

