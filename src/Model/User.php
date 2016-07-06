<?php
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
 * @method User setShortName() setShortName(string $shortName)
 * @method string getShortName() getShortName()
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
    public $properties = [
        'id',
        'username',
        'password',
        'email',
        'role',
        'displayName',
        'shortName',
        'hash',
        'status',
        'lastLogin',
        'updated',
        'created',
    ];
}
