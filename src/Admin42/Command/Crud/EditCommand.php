<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\Crud;

use Admin42\Command\Mail\SendCommand;
use Admin42\Crud\CrudEvent;
use Admin42\Model\User;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Model\ModelInterface;
use Core42\View\Model\MailModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Json\Json;
use Zend\Validator\EmailAddress;
use ZF\Console\Route;

class EditCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $tableGatewayName;


    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ModelInterface
     */
    protected $model;

    /**
     * @param string $tableGatewayName
     * @return $this
     */
    public function setTableGatewayName($tableGatewayName)
    {
        $this->tableGatewayName = $tableGatewayName;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->data = $values;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    protected function set($name, $value)
    {
        $this->data[$name] =  $value;

        return $this;
    }

    /**
     * @param $method
     * @param $params
     * @return $this
     * @throws \Exception
     */
    public function __call($method, $params)
    {
        $variableName = lcfirst(substr($method, 3));
        if (strncasecmp($method, "set", 3) === 0) {
            return $this->set($variableName, $params[0]);
        }

        throw new \Exception("Method {$method} not found");
    }

    protected function preExecute()
    {
        $this->model = $this->getTableGateway($this->tableGatewayName)->selectByPrimary((int) $this->id);

        if (!($this->model instanceof ModelInterface)) {
            $this->addError("model", "invalid model");

            return;
        }
    }

    /**
     * @return User
     */
    protected function execute()
    {
        foreach ($this->data as $name => $value) {
            if (is_array($value)) {
                $this->data[$name] = Json::encode($value);
            }
        }

        $this->model->populate($this->data);
        if ($this->model->hasChanged()) {

            if (method_exists($this->model, "getProperties")) {
                $dateTime = new \DateTime();
                $properties = $this->model->getProperties();

                if (in_array("updated", $properties) && !array_key_exists("updated", $this->data)) {
                    $this->model->setUpdated($dateTime);
                }
            }

            $this
                ->getServiceManager()
                ->get('Admin42\Crud\EventManager')
                ->trigger(CrudEvent::EVENT_EDIT_PRE, $this->model);

            $this->getTableGateway($this->tableGatewayName)->update($this->model);

            $this
                ->getServiceManager()
                ->get('Admin42\Crud\EventManager')
                ->trigger(CrudEvent::EVENT_EDIT_POST, $this->model);

        }

        return $this->model;
    }
}
