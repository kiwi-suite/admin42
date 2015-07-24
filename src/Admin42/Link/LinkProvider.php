<?php
namespace Admin42\Link;

use Admin42\Link\Adapter\AdapterInterface;

class LinkProvider
{
    /**
     * @var AdapterInterface[]
     */
    protected $adapter = [];

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
}