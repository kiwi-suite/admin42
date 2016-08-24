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
        'id' => 'Integer',
        'hash' => 'String',
        'type' => 'String',
        'value' => 'Json',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\Link';
}
