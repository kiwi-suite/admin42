<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Admin42\View\Helper;

use Admin42\Authentication\AuthenticationService;
use Core42\View\Helper\Auth;
use Zend\I18n\View\Helper\Translate;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Helper\AbstractHelper;

class Admin extends AbstractHelper
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        /* @var Auth $translator */
        $auth = $this->getView()->plugin('auth');

        if ($auth(AuthenticationService::class)->hasIdentity()) {
            $auth(AuthenticationService::class)->getIdentity()->getLocale();
        }

        return $this->config['locale'];
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        /* @var Auth $translator */
        $auth = $this->getView()->plugin('auth');

        if ($auth(AuthenticationService::class)->hasIdentity()) {
            $auth(AuthenticationService::class)->getIdentity()->getTimezone();
        }

        return $this->config['timezone'];
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
        foreach (\array_keys($messages) as $type) {
            if ($flash->hasCurrentMessages($type)) {
                $messages[$type] = \array_merge($messages[$type], $flash->getCurrentMessages($type));
                $flash->clearCurrentMessagesFromNamespace($type);
            }
            if ($flash->hasMessages($type)) {
                $messages[$type] = \array_merge($messages[$type], $flash->getMessages($type));
                $flash->clearMessagesFromNamespace($type);
            }

            foreach ($messages[$type] as &$_msg) {
                if (\is_string($_msg)) {
                    $_msg = ['title' => 'toaster.' . $type, 'message' => $_msg];
                }

                $_msg = [
                    'title' => $translator($_msg['title'], 'admin'),
                    'message' => $translator($_msg['message'], 'admin'),
                ];
            }
        }


        return 'var FLASH_MESSAGE = ' . \json_encode($messages) . ';' . PHP_EOL;
    }
}
