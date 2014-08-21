<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Selector\DataTable;

use Admin42\DataTable\AbstractDataTableSelector;
use Admin42\DataTable\AbstractTableGatewaySelector;
use Admin42\DataTable\DataTable;
use Admin42\DataTable\Decorator\DateTimeDecorator;
use Admin42\DataTable\Decorator\RoleDecorator;
use Admin42\DataTable\Mutator\DateTimeMutator;
use Admin42\Model\User;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Sql;

class UserListSelector extends AbstractTableGatewaySelector
{
    /**
     * @var string
     */
    protected $tableGateway = 'Admin42\User';

    /**
     * @param DataTable $dataTable
     * @return mixed
     */
    protected function configure(DataTable $dataTable)
    {
        $dataTable->setAjax('admin/user');

        $dataTable->addColumn(array(
            'label' => 'table.id',
            'match_name' => 'id',
            'sortable' => true,
        ));
        $dataTable->addColumn(array(
            'label' => 'table.email',
            'match_name' => 'email',
            'sortable' => true,
            'searchable' => true,
        ));
        $dataTable->addColumn(array(
            'label' => 'table.username',
            'match_name' => 'username',
            'sortable' => true,
            'searchable' => true,
        ));
        $dataTable->addColumn(array(
            'label' => 'table.display-name',
            'match_name' => 'displayName',
            'sortable' => true,
            'searchable' => true,
        ));
        $dataTable->addColumn(array(
            'label' => 'table.role',
            'match_name' => 'role',
            'sortable' => true,
            'searchable' => true,
            'decorators' => array(
                new RoleDecorator()
            ),
        ));
        $dataTable->addColumn((array(
            'label' => 'table.last-login',
            'match_name' => 'lastLogin',
            'sortable' => true,
            'decorators' => array(
                new DateTimeDecorator()
            ),
            'mutator' => new DateTimeMutator(),
        )));
        $dataTable->addColumn((array(
            'label' => 'table.created',
            'match_name' => 'created',
            'sortable' => true,
            'decorators' => array(
                new DateTimeDecorator()
            ),
            'mutator' => new DateTimeMutator(),
        )));

        $dataTable->addEditButton('admin/user/edit', array('id' => 'id'));

        $dataTable->addDeleteButton('admin/user/delete', array('id' => 'id'));
    }

    /**
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect()
    {
        $select = parent::getSelect();
        $select->where(new In('status', array(User::STATUS_ACTIVE)));

        return $select;
    }
}
