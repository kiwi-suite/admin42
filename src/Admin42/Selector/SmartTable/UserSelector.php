<?php
namespace Admin42\Selector\SmartTable;

use Core42\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;

class UserSelector extends AbstractSmartTableSelector
{
    /**
     * @return Select|string|ResultSet
     */
    protected function getSelect()
    {
        $gateway = $this->getTableGateway('Admin42\User');

        $select = $gateway->getSql()->select();

        $where = $this->getWhere();
        if (!empty($where)) {
            $select->where($where);
        }

        $order = $this->getOrder();
        if (!empty($order)) {
            $select->order($order);
        }

        return $select;
    }

    /**
     * @return array
     */
    protected function getDatabaseTypeMap()
    {
        return [
            'id' => 'Mysql/Integer',
            'lastLogin' => 'Mysql/Datetime',
            'created' => 'Mysql/Datetime',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchAbleColumns()
    {
        return ['id', 'email', 'username', 'displayName'];
    }

    /**
     * @return array
     */
    protected function getSortAbleColumns()
    {
        return ['id', 'email', 'created', 'lastLogin', 'username', 'displayName', 'role'];
    }

    /**
     * @return array
     */
    protected function getDisplayColumns()
    {
        return ['id', 'email', 'created', 'lastLogin', 'username', 'displayName', 'role'];
    }
}
