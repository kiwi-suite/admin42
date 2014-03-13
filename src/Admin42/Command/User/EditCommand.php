<?php
namespace Admin42\Command\User;

use Admin42\Model\User;
use Core42\Command\AbstractCommand;
use Core42\ValueManager\ValueManager;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Db\NoRecordExists;

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

    protected function prepareValueManager(ValueManager $valueManager)
    {

    }

    protected function preExecute()
    {
        if (!($this->user instanceof User)) {
            $this->id = (int) $this->id;
            $this->user = $this->getServiceManager()->get('Admin42\\UserTableGateway')->selectByPrimary($this->id);
        }

        if (!($this->user instanceof User) || !($this->user->getId() > 0)) {
            $this->setCommandError("id", "invalid id");

            return;
        }

        $inputFilter = new InputFilter();
        $data = array();

        if (!empty($this->username)) {
            $inputFilter->add($this->user->getInputFilter()->get("username"));
            $inputFilter->get('username')->getValidatorChain()->addValidator(
                new NoRecordExists(array(
                    'table' => $this->getServiceManager()->get('Admin42\\UserTableGateway')->getTable(),
                    'adapter' => $this->getServiceManager()->get('Admin42\\UserTableGateway')->getAdapter(),
                    'field' => 'username',
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $this->user->getId()
                    )
                ))
            );

            $data['username'] = $this->username;
        }

        if (!empty($this->email)) {
            $inputFilter->add($this->user->getInputFilter()->get("email"));
            $inputFilter->get('email')->getValidatorChain()->addValidator(
                new NoRecordExists(array(
                    'table' => $this->getServiceManager()->get('Admin42\\UserTableGateway')->getTable(),
                    'adapter' => $this->getServiceManager()->get('Admin42\\UserTableGateway')->getAdapter(),
                    'field' => 'email',
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $this->user->getId()
                    )
                ))
            );

            $data['email'] = $this->email;
        }

        if (!empty($this->password) || !empty($this->passwordRepeat)) {
            $inputFilter->add($this->user->getInputFilter()->get("password"));
            $inputFilter->add(array(
                'name' => 'passwordRepeat',
                'validators' => array(
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password',
                        ),
                    ),
                ),
            ));

            $data['password'] = $this->password;
            $data['passwordRepeat'] = $this->passwordRepeat;
        }

        $inputFilter->setData($data);
        if (!$inputFilter->isValid()) {
            $this->setCommandErrors($inputFilter->getMessages());

            return;
        }

        $data = $inputFilter->getValues();
        $this->user->hydrate($data);
    }

    protected function execute()
    {
        $this->user->setUpdated(new \DateTime());
        $this->getServiceManager()->get('Admin42\\UserTableGateway')->update($this->user);
    }
}


