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
use Admin42\TableGateway\UserTableGateway;
use Core42\Command\AbstractCommand;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\EmailAddress;

class RecoverPasswordCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $password;

    /**
     * @var User
     */
    private $user;

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
     * @param string $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

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
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setPassword(array_key_exists('password', $values) ? $values['password'] : null);
    }

    /**
     *
     */
    protected function preExecute()
    {
        $emailValidator = new EmailAddress();
        if (!$emailValidator->isValid($this->email)) {
            $this->addError("email", "invalid email address");
        }
        if (empty($this->hash)) {
            $this->addError("hash", "invalid hash");
        }

        if (empty($this->password)) {
            $this->addError("password", "invalid password");
        }

        if ($this->hasErrors()) {
            return;
        }

        $this->user = $this->getTableGateway(UserTableGateway::class)->select([
            'email' => $this->email,
            'hash' => $this->hash,
        ])->current();

        if (!($this->user instanceof User)) {
            $this->addError("user", "invalid user");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $bCrypt = new Bcrypt();
        $cryptedPassword = $bCrypt->create($this->password);

        $this->user->setPassword($cryptedPassword);
        $this->user->setHash(null);
        
        $this->getTableGateway(UserTableGateway::class)->update($this->user);
    }
}
