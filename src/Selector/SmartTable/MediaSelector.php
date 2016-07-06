<?php
namespace Admin42\Selector\SmartTable;

use Core42\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;

class MediaSelector extends AbstractSmartTableSelector
{
    /**
     * @return Select|string|ResultSet
     */
    protected function getSelect()
    {
        $gateway = $this->getTableGateway('Admin42\Media');

        $select = $gateway->getSql()->select();

        $where = $this->getWhere();
        if (!empty($where)) {
            $select->where($where);
        }

        $order = $this->getOrder();
        if (!empty($order)) {
            $select->order($order);
        } else {
            $select->order('created DESC');
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
            'updated' => 'Mysql/Datetime',
            'created' => 'Mysql/Datetime',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchAbleColumns()
    {
        return ['id', 'filename', 'category'];
    }

    /**
     * @return array
     */
    protected function getSortAbleColumns()
    {
        return ['id', 'filename', 'created'];
    }

    /**
     * @return array
     */
    protected function getDisplayColumns()
    {
        return ['id', 'directory', 'filename', 'mimeType', 'size', 'meta', 'updated', 'created'];
    }
}
