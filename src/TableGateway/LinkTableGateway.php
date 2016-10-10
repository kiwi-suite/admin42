<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
