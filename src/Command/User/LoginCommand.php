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

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\LoginHistory;
use Admin42\Model\User;
use Admin42\TableGateway\LoginHistoryTableGateway;
use Admin42\TableGateway\UserTableGateway;
use Core42\Command\AbstractCommand;
use Core42\Stdlib\DateTime;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\Validator\EmailAddress;

class LoginCommand extends AbstractCommand
{
    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * @var string
     */
    private $identity;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $identityType;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    private $ip;

    /**
     * @param string $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

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
     * @param string $ip
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        if (isset($values['password'])) {
            $this->setPassword($values['password']);
        }
        if (isset($values['identity'])) {
            $this->setIdentity($values['identity']);
        }
        if (isset($values['ip'])) {
            $this->setIp($values['ip']);
        }
    }

    /**
     *
     */
    protected function preExecute()
    {
        $this->authenticationService = $this->getServiceManager()->get(AuthenticationService::class);

        if (empty($this->identity)) {
            $this->addError('identity', "Can't be empty");
        }

        if (empty($this->password)) {
            $this->addError('password', "Can't be empty");
        }

        $emailValidator = new EmailAddress();
        $this->identityType = ($emailValidator->isValid($this->identity)) ? 'email' : 'username';

        $userTableGateway = $this->getTableGateway(UserTableGateway::class);

        $resultSet = $userTableGateway->select([
            $this->identityType => $this->identity,
        ]);

        $bCrypt = new Bcrypt();

        if ($resultSet->count() != 1) {
            $bCrypt->create('test');
            $this->addError('identity', 'Invalid username or password');

            return;
        }

        $config = $this->getServiceManager()->get('Config');

        $login = true;
        
        /** @var User $user */
        $user = $resultSet->current();
        if (!$bCrypt->verify($this->password, $user->getPassword())) {
            $this->addError('identity', 'Invalid username or password');
            $this->createLoginHistory($user->getId(), LoginHistory::STATUS_FAIL);
            
            $login = false;
        }

        if ($login && !in_array($user->getStatus(), [User::STATUS_ACTIVE])) {
            $this->addError('identity', 'Invalid username or password');
            $this->createLoginHistory($user->getId(), LoginHistory::STATUS_FAIL);

            $login = false;
        }
        
        $forceCaptcha = false;
        if ($config['project']['admin_login_captcha'] === true) {

            $loginHistoryTableGateway = $this->getTableGateway(LoginHistoryTableGateway::class);
            $select = $loginHistoryTableGateway->getSql()->select();
            $select->where->equalTo('userId', $user->getId());
            $select->order('created desc');
            $select->limit(5);

            $result = $loginHistoryTableGateway->selectWith($select);

            if ($result->count() == 5) {
                $failCount = 0;
                foreach ($result as $loginHistory) {
                    /** @var LoginHistory $loginHistory */
                    if ($loginHistory->getStatus() === LoginHistory::STATUS_SUCCESS) {
                        break;
                    }
                    $failCount++;
                }
                if ($failCount === 5) {
                    $forceCaptcha = true;
                }
            }

            if ($forceCaptcha) {

                $this->addError('captcha', 'force captcha');
                
                $container = new Container('login');
                $container->success = $login;
                $container->user_id = $user->getId();
                
                return;
            }
        }

        $this->user = $user;
    }

    /**
     *
     */
    protected function execute()
    {
        $authResult = new Result(
            Result::SUCCESS,
            $this->user->getId()
        );

        $this->authenticationService->setAuthResult($authResult);
        $this->authenticationService->authenticate();

        $this->user->setLastLogin(new DateTime());
        $this->getTableGateway(UserTableGateway::class)->update($this->user);

        $this->createLoginHistory($this->user->getId(), LoginHistory::STATUS_SUCCESS);
    }

    /**
     * @param int $userId
     * @param string $status
     */
    protected function createLoginHistory($userId, $status)
    {
        $history = new LoginHistory();
        $history->setUserId($userId)
            ->setStatus($status)
            ->setCreated(new \DateTime());

        if (!empty($this->ip)) {
            $history->setIp($this->ip);
        }

        $this->getTableGateway(LoginHistoryTableGateway::class)->insert($history);
    }

    
}
