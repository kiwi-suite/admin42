<?php
namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class TagTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'admin42_tag';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'integer',
        'tag' => 'string',
        'namespace' => 'string',
        'created' => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\Tag';
}
