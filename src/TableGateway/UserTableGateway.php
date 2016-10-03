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
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'integer',
        'username' => 'string',
        'password' => 'string',
        'email' => 'string',
        'role' => 'string',
        'displayName' => 'string',
        'shortName' => 'string',
        'hash' => 'string',
        'status' => 'string',
        'payload' => 'json',
        'lastLogin' => 'dateTime',
        'locale' => 'string',
        'timezone' => 'string',
        'updated' => 'dateTime',
        'created' => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\User';
}
