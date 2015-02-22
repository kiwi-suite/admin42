<?php
namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class UserTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'admin42_user';

    /**
     * @var array
     */
    protected $databaseTypeMap = array();

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\User';


}
