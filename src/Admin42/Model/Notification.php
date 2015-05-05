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
 * @method Notification setId() setId(int $id)
 * @method int getId() getId()
 * @method Notification setUserId() setUserId(int $userId)
 * @method int getUserId() getUserId()
 * @method Notification setText() setText(string $text)
 * @method string getText() getText()
 * @method Notification setRoute() setRoute(string $route)
 * @method string getRoute() getRoute()
 * @method Notification setRouteParams() setRouteParams(string $routeParams)
 * @method string getRouteParams() getRouteParams()
 * @method Notification setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
 */
class Notification extends AbstractModel
{

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'userId',
        'text',
        'route',
        'routeParams',
        'created'
    ];
}
