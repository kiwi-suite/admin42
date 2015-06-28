<?php
namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class MediaTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'admin42_media';

    /**
     * @var array
     */
    protected $databaseTypeMap = array();

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\Media';


}
