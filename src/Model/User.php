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
use Core42\Permission\IdentityRoleProviderInterface;
use Core42\Stdlib\DateTime;

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
 * @method User setDisplayName() setDisplayName(string $displayName)
 * @method string getDisplayName() getDisplayName()
 * @method User setShortName() setShortName(string $shortName)
 * @method string getShortName() getShortName()
 * @method User setHash() setHash(string $hash)
 * @method string getHash() getHash()
 * @method User setStatus() setStatus(string $status)
 * @method string getStatus() getStatus()
 * @method User setPayload() setPayload(array $payload)
 * @method array getPayload() getPayload()
 * @method User setLastLogin() setLastLogin(DateTime $lastLogin)
 * @method DateTime getLastLogin() getLastLogin()
 * @method User setLocale() setLocale($locale)
 * @method string getLocale() getLocale()
 * @method User setTimezone() setTimezone($timezone)
 * @method string getTimezone() getTimezone()
 * @method User setUpdated() setUpdated(DateTime $updated)
 * @method DateTime getUpdated() getUpdated()
 * @method User setCreated() setCreated(DateTime $created)
 * @method DateTime getCreated() getCreated()
 */
class User extends AbstractModel implements IdentityRoleProviderInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
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
        'shortName',
        'hash',
        'status',
        'payload',
        'lastLogin',
        'locale',
        'timezone',
        'updated',
        'created',
    ];

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->get('role');
    }
}
