<?php
namespace Admin42\Command\User;

use Admin42\Model\User;
use Core42\Command\AbstractCommand;

class EditCommand extends AbstractCommand
{
    /**
     * @var int
     *
     */
    private $id;

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
    private $passwordRepeat;

    /**
     * @var string
     */
    private $email;

    /**
     * @var User
     */
    private $user;

    /**
     * @param int $id
     * @return \Admin42\Command\User\EditCommand
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $username
     * @return \Admin42\Command\User\EditCommand
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $password
     * @return \Admin42\Command\User\EditCommand
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $passwordRepeat
     * @return \Admin42\Command\User\EditCommand
     */
    public function setPasswordRepeat($passwordRepeat)
    {
        $this->passwordRepeat = $passwordRepeat;

        return $this;
    }

    /**
     * @param string $email
     * @return \Admin42\Command\User\EditCommand
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param User $user
     * @return \Admin42\Command\User\EditCommand
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    protected function preExecute()
    {

    }

    protected function execute()
    {

    }
}


