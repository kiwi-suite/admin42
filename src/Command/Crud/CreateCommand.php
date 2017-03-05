<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Admin42\Command\Crud;

use Admin42\Crud\CrudEvent;
use Admin42\Model\User;
use Core42\Command\AbstractCommand;
use Core42\Stdlib\DateTime;

class CreateCommand extends AbstractCommand
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
     * @param string $tableGatewayName
     * @return $this
     */
    public function setTableGatewayName($tableGatewayName)
    {
        $this->tableGatewayName = $tableGatewayName;

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
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * @param $method
     * @param $params
     * @throws \Exception
     * @return $this
     */
    public function __call($method, $params)
    {
        $variableName = \lcfirst(\mb_substr($method, 3));
        if (\strncasecmp($method, 'set', 3) === 0) {
            return $this->set($variableName, $params[0]);
        }

        throw new \Exception("Method {$method} not found");
    }

    /**
     * @return User
     */
    protected function execute()
    {
        $model = $this->getTableGateway($this->tableGatewayName)->getModel();

        if (\method_exists($model, 'getProperties')) {
            $dateTime = new DateTime();
            $properties = $model->getProperties();

            if (\in_array('updated', $properties) && !\array_key_exists('updated', $this->data)) {
                $this->setUpdated($dateTime);
            }

            if (\in_array('created', $properties) && !\array_key_exists('created', $this->data)) {
                $this->setCreated($dateTime);
            }
        }

        $model->populate($this->data);
        $this->getTableGateway($this->tableGatewayName)->insert($model);

        $this
            ->getServiceManager()
            ->get('Admin42\Crud\EventManager')
            ->trigger(CrudEvent::EVENT_ADD, $model);

        return $model;
    }
}
