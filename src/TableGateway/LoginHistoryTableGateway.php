<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class LoginHistoryTableGateway extends AbstractTableGateway
{

    /**
     * @var string
     */
    protected $table = 'admin42_user_loginhistory';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id' => 'integer',
        'userId' => 'integer',
        'status' => 'string',
        'ip' => 'string',
        'created' => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = 'Admin42\\Model\\LoginHistory';
}
