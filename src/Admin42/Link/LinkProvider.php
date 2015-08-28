<?php
namespace Admin42\Link;

use Admin42\Link\Adapter\AdapterInterface;
use Admin42\TableGateway\LinkTableGateway;
use Zend\Cache\Storage\StorageInterface;
use Zend\Json\Json;

class LinkProvider
{
    /**
     * @var AdapterInterface[]
     */
    protected $adapter = [];

    /**
     * @var LinkTableGateway
     */
    protected $linkTableGateway;

    /**
     * @var StorageInterface
     */
    protected $cache;

    public function __construct(LinkTableGateway $linkTableGateway, StorageInterface $cache)
    {
        $this->linkTableGateway = $linkTableGateway;

        $this->cache = $cache;
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
        return $this->adapter[$name]->assemble($value);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    public function getDisplayName($name, $value)
    {
        return $this->adapter[$name]->getDisplayName($value);
    }

    /**
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function assembleById($id)
    {
        if (empty($id)) {
            return "";
        }

        if (!$this->cache->hasItem($id)) {
            $link = $this->linkTableGateway->selectByPrimary((int) $id);
            $this->cache->setItem($id, $link);
        }
        $link = $this->cache->getItem($id);
        if (empty($link)) {
            return "";
        }

        return $this->assemble($link->getType(), Json::decode($link->getValue(), Json::TYPE_ARRAY));
    }
}
