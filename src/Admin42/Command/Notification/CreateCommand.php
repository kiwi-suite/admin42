<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\Notification;

use Admin42\Model\Notification;
use Admin42\Model\User;
use Core42\Command\AbstractCommand;
use Zend\Db\ResultSet\ResultSet;

class CreateCommand extends AbstractCommand
{
    /**
     * @var array
     */
    private $userIds = array();

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $route;

    /**
     * @var
     */
    private $routeParams;

    /**
     * @param User|int $user
     * @return $this
     */
    public function setUser($user)
    {
        if ($user instanceof User) {
            $this->userIds = array($user->getId());

            return $this;
        }

        $this->userIds = array((int) $user);

        return $this;
    }

    /**
     * @param array|ResultSet|\Closure $users
     * @return $this
     */
    public function setUsers($users)
    {
        if ($users instanceof ResultSet) {
            $this->userIds = array();
            foreach ($users as $_user) {
                $this->userIds[] = $_user->getId();
            }
        } elseif (is_array($users)){
            $this->userIds = $users;
        } elseif ($users instanceof \Closure) {
            $this->userIds = (array) $users();
        }

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @param mixed $routeParams
     * @return $this
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!is_array($this->userIds)) {
            $this->addError("user", "invalid users");

            return ;
        }

        if (empty($this->text)) {
            $this->addError("text", "invalid text");

            return;
        }

        if (!empty($this->routeParams)) {
            $this->routeParams = json_encode($this->routeParams);
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        if (empty($this->userIds)) {
            return;
        }

        $datetime = new \DateTime();
        foreach ($this->userIds as $userId) {
            $notification = new Notification();
            $notification->setUserId($userId)
                ->setRoute($this->route)
                ->setRouteParams($this->routeParams)
                ->setText($this->text)
                ->setCreated($datetime);

            $this->getTableGateway('Admin42\Notification')->insert($notification);
        }
    }
}
