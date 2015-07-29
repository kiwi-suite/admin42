<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Crud\Service;

use Admin42\Crud\AbstractOptions;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;

class CrudOptionsPluginManager extends AbstractPluginManager
{
    /**
     * @param ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        $this->setShareByDefault(false);

        parent::__construct($configuration);

        $this->addAbstractFactory(new CrudOptionsFallbackAbstractFactory(), false);
    }

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @throws \RuntimeException
     * @return void
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof AbstractOptions) {
            return;
        }

        throw new \RuntimeException(sprintf(
            "Plugin of type %s is invalid; must implement \\Admin42\\Crud\\AbstractOptions",
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
