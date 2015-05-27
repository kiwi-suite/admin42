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
use Zend\I18n\View\Helper\Translate;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Helper\AbstractHelper;

class Admin extends AbstractHelper
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $jsonTemplates = [];

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

    /**
     * @return string
     */
    public function flashMessenger()
    {
        $messages = [
            FlashMessenger::NAMESPACE_ERROR => [],
            FlashMessenger::NAMESPACE_WARNING => [],
            FlashMessenger::NAMESPACE_SUCCESS => [],
            FlashMessenger::NAMESPACE_INFO => [],
        ];

        /** @var Translate $translator */
        $translator = $this->getView()->plugin('translate');

        /** @var \Zend\View\Helper\FlashMessenger $flash */
        $flash = $this->getView()->plugin('flashMessenger');
        foreach (array_keys($messages) as $type) {
            if ($flash->hasCurrentMessages($type)) {
                $messages[$type] = array_merge($messages[$type], $flash->getCurrentMessages($type));
                $flash->clearCurrentMessagesFromNamespace($type);
            }
            if ($flash->hasMessages($type)) {
                $messages[$type] = array_merge($messages[$type], $flash->getMessages($type));
                $flash->clearMessagesFromNamespace($type);
            }

            foreach ($messages[$type] as &$_msg) {
                if (is_string($_msg)) {
                    $_msg = ['title' => 'toaster.'.$type, 'message' => $_msg];
                }

                $_msg = [
                    'title' => $translator($_msg['title'], 'admin'),
                    'message' => $translator($_msg['message'], 'admin'),
                ];
            }
        }


        return "var FLASH_MESSAGE = " . json_encode($messages) . ";" . PHP_EOL;
    }

    /**
     * @param string $id
     * @param mixed $value
     * @return $this
     */
    public function addJsonTemplate($id, $value)
    {
        $this->jsonTemplates[$id] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getJsonTemplates()
    {
        $templates = [];

        foreach ($this->jsonTemplates as $id => $value) {
            $templates[] = sprintf('<script id="%s" type="application/json">%s</script>', $id, json_encode($value));
        }

        return implode(PHP_EOL, $templates);
    }
}
