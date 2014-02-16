<?php

namespace Admin42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class UserTableGateway extends AbstractTableGateway
{

    public $table = 'user';

    public $modelPrototype = 'Admin42\Model\User';


}

