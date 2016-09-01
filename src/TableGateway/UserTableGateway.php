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
        'id' => 'Integer',
        'username' => 'String',
        'password' => 'String',
        'email' => 'String',
        'role' => 'String',
        'displayName' => 'String',
        'shortName' => 'String',
        'hash' => 'String',
        'status' => 'String',
        'payload' => 'Json',
        'lastLogin' => 'DateTime',
        'updated' => 'DateTime',
        'created' => 'DateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\User';
}
