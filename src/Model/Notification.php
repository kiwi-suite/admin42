<?php
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
    public $properties = [
        'id',
        'userId',
        'text',
        'route',
        'routeParams',
        'created',
    ];
}
