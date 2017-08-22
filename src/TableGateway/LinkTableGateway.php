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
