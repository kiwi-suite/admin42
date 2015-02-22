<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Admin extends AbstractHelper
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function angularBootstrap()
    {
        return "angular.element(document).ready(function(){"
            . "angular.bootstrap(document, ".json_encode($this->config['angular']['modules']).");"
            . "});" . PHP_EOL;
    }
}
