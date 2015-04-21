<?php
namespace Admin42\Selector\SmartTable;

class UserSelector extends AbstractSmartTableSelector
{
    protected $tableGateway = 'Admin42\User';

    /**
     * @var null|array
     */
    protected $columns = ['id', 'email', 'created'];

    /**
     * @var null|array
     */
    protected $searchAbleColumns = ['id', 'email'];

    /**
     * @var array
     */
    protected $sortAbleColumns = ['id', 'email'];
}
