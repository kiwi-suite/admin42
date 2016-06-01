<?php
namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class NotificationTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'admin42_notification';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'Integer',
        'userId' => 'Integer',
        'text' => 'String',
        'route' => 'String',
        'routeParams' => 'String',
        'created' => 'DateTime',
    ];

    /**
     * @var boolean
     */
    protected $useMetaDataFeature = false;

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\Notification';
}
