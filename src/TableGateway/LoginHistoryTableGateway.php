<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
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
