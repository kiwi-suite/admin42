<?php
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
    public $properties = [
        'id',
        'userId',
        'status',
        'ip',
        'created',
    ];


}
