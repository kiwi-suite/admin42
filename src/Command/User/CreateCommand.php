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
    const EMAIL_TYPE_PASSWORD = 'password';
    const EMAIL_TYPE_ACTIVATION = 'activation';

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
    protected $cryptedPassword;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var bool
     */
    protected $enableEmail = true;

    /**
     * @var string
     */
    protected $emailType = self::EMAIL_TYPE_PASSWORD;

    /**
     * @var string
     */
    protected $locale = "en-US";

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
     * @param bool $enableEmail
     * @return $this
     */
    public function setEnableEmail($enableEmail)
    {
        $this->enableEmail = (bool) $enableEmail;

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
     * @param string $shortName
     * @return $this
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

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
        $this->setPassword(\array_key_exists('password', $values) ? $values['password'] : null);
        $this->setEmail(\array_key_exists('email', $values) ? $values['email'] : null);
        $this->setStatus(\array_key_exists('status', $values) ? $values['status'] : null);
        $this->setDisplayName(\array_key_exists('displayName', $values) ? $values['displayName'] : null);
        $this->setRole(\array_key_exists('role', $values) ? $values['role'] : null);
        $this->setPayload(\array_key_exists('payload', $values) ? $values['payload'] : []);
        $this->setShortName(\array_key_exists('shortName', $values) ? $values['shortName'] : null);
        $this->setLocale(\array_key_exists('locale', $values) ? $values['locale'] : 'en-US');
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
        } elseif (!\in_array($this->status, [User::STATUS_INACTIVE, User::STATUS_ACTIVE])) {
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
            $this->emailType = self::EMAIL_TYPE_ACTIVATION;

            $set = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789!@#$%&*?';
            for ($i = 0; $i < 12; $i++) {
                $this->password .= $set[\array_rand(\str_split($set))];
            }
        }

        if (empty($this->shortName)) {
            $this->shortName = \mb_strtoupper(\mb_substr($this->email, 0, 1));
            if (!empty($this->displayName)) {
                $parts = \explode(' ', $this->displayName);
                $this->shortName = \mb_strtoupper(\mb_substr($parts[0], 0, 1));
                if (\count($parts) > 1) {
                    $this->shortName .= \mb_strtoupper(\mb_substr($parts[1], 0, 1));
                }
            }
        }
        if (\mb_strlen($this->shortName) > 2) {
            $this->shortName = \mb_substr($this->shortName, 0, 2);
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
                ->setLocale($this->locale)
                ->setTimezone('Europe/Vienna')
                ->setUpdated($dateTime)
                ->setCreated($dateTime);

        if ($this->emailType === self::EMAIL_TYPE_ACTIVATION) {
            $hash = \sha1($user->getPassword() . $user->getId() . \uniqid());
            $user->setHash($hash);
        }

        $this->getServiceManager()->get('TableGateway')->get(UserTableGateway::class)->insert($user);

        $this->consoleOutput("<info>User {$this->email} created</info>");

        if ($this->enableEmail === true) {
            $httpRouter = $this->getServiceManager()->get('HttpRouter');

            $mailViewModel = new MailModel([
                'username' => $this->email,
                'password' => $this->password,
                'emailType' => $this->emailType,
                'loginUrl' => $httpRouter->assemble([], ['name' => 'admin/login']),
                'resetPasswordUrl' => $httpRouter->assemble(
                    [
                        'email' => \urlencode($user->getEmail()),
                        'hash' => (\mb_strlen($user->getHash())) ? $user->getHash() : 'unset',
                    ],
                    [
                        'name' => 'admin/recover-password',
                    ]
                ),
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
