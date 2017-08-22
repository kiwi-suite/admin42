<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\Navigation\Page;

use Core42\Navigation\Page\AbstractPage;
use Core42\Navigation\Page\PageInterface;
use Zend\Router\RouteMatch;
use Zend\Router\RouteStackInterface;

class Page extends AbstractPage implements PageInterface
{
    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param RouteStackInterface $router
     * @param RouteMatch $routeMatch
     */
    public function __construct(RouteStackInterface $router, RouteMatch $routeMatch = null)
    {
        $this->router = $router;
        $this->routeMatch = $routeMatch;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        $route = $this->getRoute();
        if (empty($route)) {
            return '';
        }

        return $this->router->assemble($this->params, ['name' => $route]);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if (empty($this->routeMatch)) {
            return false;
        }

        if (\mb_strlen($this->getRoute()) > \mb_strlen($this->routeMatch->getMatchedRouteName())) {
            return false;
        }

        if (\mb_substr($this->routeMatch->getMatchedRouteName(), 0, \mb_strlen($this->getRoute())) != $this->getRoute()) {
            return false;
        }

        if (empty($this->params)) {
            return true;
        }

        foreach ($this->params as $name => $value) {
            if ($this->routeMatch->getParam($name) !== $value) {
                return false;
            }
        }

        return true;
    }
}
