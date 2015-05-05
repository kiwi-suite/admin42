<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */
namespace Admin42\Model;

use Core42\Model\AbstractModel;

/**
 * @method User setId() setId(int $id)
 * @method int getId() getId()
 * @method User setUsername() setUsername(string $username)
 * @method string getUsername() getUsername()
 * @method User setPassword() setPassword(string $password)
 * @method string getPassword() getPassword()
 * @method User setEmail() setEmail(string $email)
 * @method string getEmail() getEmail()
 * @method User setRole() setRole(string $role)
 * @method string getRole() getRole()
 * @method User setDisplayName() setDisplayName(string $displayName)
 * @method string getDisplayName() getDisplayName()
 * @method User setHash() setHash(string $hash)
 * @method string getHash() getHash()
 * @method User setStatus() setStatus(string $status)
 * @method string getStatus() getStatus()
 * @method User setLastLogin() setLastLogin(\DateTime $lastLogin)
 * @method \DateTime getLastLogin() getLastLogin()
 * @method User setUpdated() setUpdated(\DateTime $updated)
 * @method \DateTime getUpdated() getUpdated()
 * @method User setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
 */
class User extends AbstractModel
{
    const STATUS_ACTIVE = "active";
    const STATUS_INACTIVE = "inactive";

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'username',
        'password',
        'email',
        'role',
        'displayName',
        'hash',
        'status',
        'lastLogin',
        'updated',
        'created'
    ];
}
