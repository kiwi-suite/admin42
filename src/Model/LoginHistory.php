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

namespace Admin42\Model;

use Core42\Model\AbstractModel;

/**
 * @method LoginHistory setId() setId(int $id)
 * @method int getId() getId()
 * @method LoginHistory setUserId() setUserId(int $userId)
 * @method int getUserId() getUserId()
 * @method LoginHistory setStatus() setStatus(string $status)
 * @method string getStatus() getStatus()
 * @method LoginHistory setIp() setIp(string $ip)
 * @method string getIp() getIp()
 * @method LoginHistory setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
 */
class LoginHistory extends AbstractModel
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'userId',
        'status',
        'ip',
        'created',
    ];
}
