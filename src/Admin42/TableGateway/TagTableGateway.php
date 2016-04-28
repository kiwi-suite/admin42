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
        'id' => 'Integer',
        'tag' => 'String',
        'namespace' => 'String',
        'created' => 'DateTime',
    ];

    /**
     * @var boolean
     */
    protected $useMetaDataFeature = false;

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\Tag';


}
