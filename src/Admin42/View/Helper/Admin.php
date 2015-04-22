<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper;

use Admin42\Model\User;
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

    /**
     * @param User $user
     * @return string
     */
    public function getUserDisplayName(User $user)
    {
        $displayName = $user->getDisplayName();
        if (empty($displayName)) {
            $displayName = $user->getUsername();
        }
        if (empty($displayName)) {
            $displayName = $user->getEmail();
        }

        return $displayName;
    }

    /**
     * @return string
     */
    public function angularBootstrap()
    {
        $appConfig = [
            'locale' => \Locale::getDefault(),
            'defaultDateTimeFormat' => 'LLL',
            'timezone' => date_default_timezone_get(),
        ];
        return "var APP;"
            . "angular.element(document).ready(function(){"
            . "APP = angular.module('APP', ".json_encode($this->config['angular']['modules']).");"
            . "APP.constant('appConfig', ".json_encode($appConfig).");"
            . "angular.bootstrap(document, ['APP']);"
            . "});" . PHP_EOL;
    }
}
