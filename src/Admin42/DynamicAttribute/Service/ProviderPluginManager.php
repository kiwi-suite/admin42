<?php
namespace Admin42\DynamicAttribute\Service;

use Admin42\DynamicAttribute\ProviderInterface;
use Zend\ServiceManager\AbstractPluginManager;

class ProviderPluginManager extends AbstractPluginManager
{
    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws \RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof ProviderInterface) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement Admin42\\DynamicAttribute\\ProviderInterface",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
