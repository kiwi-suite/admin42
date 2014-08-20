<?php
namespace Admin42\DataTable;

abstract class AbstractTableGatewaySelector extends AbstractDataTableSelector
{
    protected $tableGateway = '';

    /**
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect()
    {
        return $this->getSql()->select();
    }

    /**
     * @return Sql
     */
    protected function getSql()
    {
        return $this->getTableGateway($this->tableGateway)->getSql();
    }

    /**
     * @return \Core42\Model\AbstractModel|\Core42\Model\DefaultModel|string
     */
    protected function getModel()
    {
        $model = $this->getTableGateway($this->tableGateway)->getModelPrototype();
        if (is_string($model)) {
            $model = new $model();
        }
        return $model;
    }

    /**
     * @return \Core42\Hydrator\ModelHydrator
     */
    protected function getHydrator()
    {
        return $this->getTableGateway($this->tableGateway)->getHydrator();
    }
}
