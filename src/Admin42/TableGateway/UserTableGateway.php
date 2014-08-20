<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class UserTableGateway extends AbstractTableGateway
{
    /**
     * @var string
     */
    public $table = 'admin42_user';

    /**
     * @var string
     */
    public $modelPrototype = 'Admin42\Model\User';
}
