<?php
namespace Admin42\Selector\SmartTable;

class UserSelector extends AbstractSmartTableSelector
{
    protected $tableGateway = 'Admin42\User';

    /**
     * @var null|array
     */
    protected $columns = ['id', 'email', 'created', 'lastLogin', 'username', 'displayName', 'role'];

    /**
     * @var null|array
     */
    protected $searchAbleColumns = ['id', 'email', 'username', 'displayName'];

    /**
     * @var array
     */
    protected $sortAbleColumns = ['id', 'email', 'created', 'lastLogin', 'username', 'displayName', 'role'];
}
