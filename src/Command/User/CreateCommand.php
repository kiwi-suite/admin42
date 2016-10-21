<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\Command\User;

use Admin42\Command\Mail\SendCommand;
use Admin42\Model\User;
use Admin42\TableGateway\UserTableGateway;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Stdlib\DateTime;
use Core42\View\Model\MailModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\EmailAddress;
use ZF\Console\Route;

class CreateCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

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
     * @var string
     */
    private $shortName;

    /**
     * @var array
     */
    private $payload = [];

    /**
     * @var bool
     */
    private $enablePasswordEmail = true;

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
     * @param bool $enablePasswordEmail
     * @return $this
     */
    public function setEnablePasswordEmail($enablePasswordEmail)
    {
        $this->enablePasswordEmail = (bool) $enablePasswordEmail;

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
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setUsername(array_key_exists('username', $values) ? $values['username'] : null);
        $this->setPassword(array_key_exists('password', $values) ? $values['password'] : null);
        $this->setEmail(array_key_exists('email', $values) ? $values['email'] : null);
        $this->setStatus(array_key_exists('status', $values) ? $values['status'] : null);
        $this->setDisplayName(array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(array_key_exists('role', $values) ? $values['role'] : null);
        $this->setPayload(array_key_exists('payload', $values) ? $values['payload'] : []);
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (empty($this->email)) {
            $this->addError('email', "email can't be empty");
        }

        if (empty($this->role)) {
            $this->addError('role', 'invalid role');
        }

        if ($this->status === null) {
            $this->status = User::STATUS_ACTIVE;
        } elseif (!in_array($this->status, [User::STATUS_INACTIVE, User::STATUS_ACTIVE])) {
            $this->addError('status', "invalid status '{$this->status}'");
        }

        $this->username = (empty($this->username)) ? null : $this->username;
        $this->displayName = (empty($this->displayName)) ? null : $this->displayName;

        $emailValidator = new EmailAddress();

        if (!$emailValidator->isValid($this->email)) {
            $this->addError('email', 'invalid email address');
        }

        if (!empty($this->username)) {
            if ($emailValidator->isValid($this->username)) {
                $this->addError('username', "Username can't be an email");
            }
        }

        if (empty($this->password)) {
            $set = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789!@#$%&*?';
            for ($i = 0; $i < 12; $i++) {
                $this->password .= $set[array_rand(str_split($set))];
            }
        }

        $this->shortName = strtoupper(substr($this->email, 0, 1));
        if (!empty($this->displayName)) {
            $parts = explode(' ', $this->displayName);
            $this->shortName = strtoupper($parts[0]);
            if (count($parts) > 1) {
                $this->shortName .= $parts[1];
            }
        }

        $bCrypt = new Bcrypt();
        $this->cryptedPassword = $bCrypt->create($this->password);
    }

    /**
     * @return User
     */
    protected function execute()
    {
        $dateTime = new DateTime();

        $user = new User();
        $user->setUsername($this->username)
                ->setPassword($this->cryptedPassword)
                ->setEmail($this->email)
                ->setDisplayName($this->displayName)
                ->setRole($this->role)
                ->setStatus($this->status)
                ->setShortName($this->shortName)
                ->setPayload($this->payload)
                ->setLocale('en-US')
                ->setTimezone('Europe/Vienna')
                ->setUpdated($dateTime)
                ->setCreated($dateTime);


        $this->getServiceManager()->get('TableGateway')->get(UserTableGateway::class)->insert($user);

        $this->consoleOutput("<info>User {$this->email} created</info>");

        if ($this->enablePasswordEmail === true) {
            $httpRouter = $this->getServiceManager()->get('HttpRouter');

            $mailViewModel = new MailModel([
                'username' => $this->email,
                'password' => $this->password,
                'loginUrl' => $httpRouter->assemble([], ['name' => 'admin/login']),
            ]);
            $mailViewModel->setHtmlTemplate('mail/admin42/scripts/create-account.html.phtml');
            $mailViewModel->setPlainTemplate('mail/admin42/scripts/create-account.plain.phtml');

            /** @var SendCommand $mailSending */
            $mailSending = $this->getCommand(SendCommand::class);
            $mailSending->setSubject('Account created')
                ->addTo($this->email)
                ->setBody($mailViewModel)
                ->run();
        }

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
