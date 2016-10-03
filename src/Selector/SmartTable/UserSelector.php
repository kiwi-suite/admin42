<?php
namespace Admin42\Selector\SmartTable;

use Admin42\TableGateway\UserTableGateway;
use Core42\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class UserSelector extends AbstractSmartTableSelector
{
    /**
     * @return Select|string|ResultSet
     */
    protected function getSelect()
    {
        $gateway = $this->getTableGateway(UserTableGateway::class);

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
            'id' => 'integer',
            'lastLogin' => 'dateTime',
            'created' => 'dateTime',
        ];
    }

    /**
     * @return PredicateSet|Where
     */
    protected function getWhere()
    {
        $where = parent::getWhere();

        $excludeDeleted = new Where();
        $excludeDeleted->notIn('status', ['inactive']);

        if (empty($where)) {
            return $excludeDeleted;
        }

        return new PredicateSet([$where, $excludeDeleted], PredicateSet::COMBINED_BY_AND);
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
