<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\Link;

use Admin42\Link\Adapter\AdapterInterface;
use Admin42\Model\Link;
use Admin42\Selector\LinkSelector;
use Admin42\TableGateway\LinkTableGateway;
use Psr\Cache\CacheItemPoolInterface;

class LinkProvider
{
    /**
     * @var AdapterInterface[]
     */
    protected $adapter = [];
    /**
     * @var LinkSelector
     */
    private $linkSelector;


    /**
     * LinkProvider constructor.
     * @param LinkSelector $linkSelector
     */
    public function __construct(LinkSelector $linkSelector)
    {

        $this->linkSelector = $linkSelector;
    }

    /**
     * @param string $name
     * @param AdapterInterface $adapter
     */
    public function addAdapter($name, AdapterInterface $adapter)
    {
        $this->adapter[$name] = $adapter;
    }

    /**
     * @param string $name
     */
    public function removeAdapter($name)
    {
        unset($this->adapter[$name]);
    }

    /**
     * @param string $name
     * @return AdapterInterface
     */
    public function getAdapter($name)
    {
        return $this->adapter[$name];
    }

    /**
     * @return array
     */
    public function getAvailableAdapters()
    {
        return array_keys($this->adapter);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    public function assemble($name, $value)
    {
        if (!isset($this->adapter[$name])) {
            return '';
        }

        return $this->adapter[$name]->assemble($value);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    public function getDisplayName($name, $value)
    {
        if (!isset($this->adapter[$name])) {
            return '';
        }

        return $this->adapter[$name]->getDisplayName($value);
    }

    /**
     * @param int $id
     * @return Link
     */
    public function getLink($id)
    {
        return $this->linkSelector->setLinkId($id)->getResult();
    }

    /**
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function assembleById($id)
    {
        if (empty($id)) {
            return '';
        }

        $link = $this->getLink($id);

        if (empty($link)) {
            return '';
        }

        return $this->assemble($link->getType(), $link->getValue());
    }
}
