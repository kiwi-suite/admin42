<?php
namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class LinkTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'admin42_link';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'integer',
        'hash' => 'string',
        'type' => 'string',
        'value' => 'json',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\Link';
}
