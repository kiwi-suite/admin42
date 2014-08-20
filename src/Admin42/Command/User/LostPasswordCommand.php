<?php
namespace Admin42\Command\User;

use Admin42\Command\Mail\SendCommand;
use Admin42\Model\User;
use Core42\Command\AbstractCommand;
use Core42\View\Model\MailModel;

class LostPasswordCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $hash;

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
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setEmail(array_key_exists('email', $values) ? $values['email'] : null);
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!empty($this->userId)) {
            $this->user = $this->getTableGateway('Admin42\User')->selectByPrimary((int) $this->userId);
        } elseif (!empty($this->email)) {
            $this->user = $this->getTableGateway('Admin42\User')->select(array('email' => $this->email))->current();
        }

        if (!($this->user instanceof User)) {
            $this->addError("user", "invalid user");

            return;
        }

        do {
            $hash = sha1($this->user->getPassword() . $this->user->getId() . uniqid());
            $found = $this->getTableGateway('Admin42\User')->select(array('hash' => $hash))->count() > 0;
        } while($found);

        $this->hash = $hash;
    }

    protected function execute()
    {
        $this->user->setHash($this->hash)
            ->setUpdated(new \DateTime());

        $this->getTableGateway('Admin42\User')->update($this->user);

        $url = $this->getServiceManager()->get('HttpRouter')->assemble(array(
            'email' => urlencode($this->user->getEmail()),
            'hash' => $this->hash,
        ), array('name' => 'admin/recover-password'));

        $mailModel = new MailModel(array(
            'recoverUrl' => $url,
        ));
        $mailModel->setHtmlTemplate("mail/admin42/scripts/lost-password.html.phtml");
        $mailModel->setPlainTemplate("mail/admin42/scripts/lost-password.plain.phtml");

        /** @var SendCommand $mailSending */
        $mailSending = $this->getCommand('Admin42\Mail\Send');
        $mailSending->setSubject('Recover account')
            ->addTo($this->user->getEmail())
            ->setBody($mailModel)
            ->run();
    }
}
