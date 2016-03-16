<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper;

use Admin42\Media\MediaOptions;
use Admin42\Model\User;
use Admin42\TableGateway\UserTableGateway;
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
     * @var array
     */
    private $htmlTemplates = [];

    /**
     * @var UserTableGateway
     */
    private $userTableGateway;

    /**
     * @var MediaOptions
     */
    private $mediaOptions;

    /**
     * @var array
     */
    private $userCache = [];

    /**
     * @var string
     */
    private $mediaUrls;

    /**
     * @param array $config
     * @param UserTableGateway $userTableGateway
     * @param MediaOptions $mediaOptions
     */
    public function __construct(
        array $config,
        UserTableGateway $userTableGateway,
        MediaOptions $mediaOptions,
        $mediaUrls
    ) {
        $this->config = $config;
        $this->userTableGateway = $userTableGateway;
        $this->mediaOptions = $mediaOptions;
        $this->mediaUrls = $mediaUrls;
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
    public function getDisplayTimezone()
    {
        return $this->config['display-timezone'];
    }

    /**
     * @param int $userId
     * @return string
     * @throws \Exception
     */
    public function getUserShortNameNameById($userId)
    {
        if (array_key_exists($userId, $this->userCache)) {
            return $this->userCache[$userId]->getShortName();
        }

        $user = $this->userTableGateway->selectByPrimary($userId);
        if ($user !== null) {
            $this->userCache[$userId] = $user;
            return $this->userCache[$userId]->getShortName();
        }

        return '';
    }

    /**
     * @param int $userId
     * @return string
     * @throws \Exception
     */
    public function getUserDisplayNameById($userId)
    {
        if (array_key_exists($userId, $this->userCache)) {
            return $this->getUserDisplayName($this->userCache[$userId]);
        }

        $user = $this->userTableGateway->selectByPrimary($userId);
        if ($user !== null) {
            $this->userCache[$userId] = $user;
            return $this->getUserDisplayName($this->userCache[$userId]);
        }

        return '';
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
            'displayTimezone' => $this->getDisplayTimezone()
        ];

        $this->addJsonTemplate("mediaConfig", [
            "baseUrl" => $this->mediaUrls,
            "dimensions" => $this->mediaOptions->getDimensions(),
        ]);

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
     * @param string $name
     * @return string
     */
    public function generateAngularModelName($name)
    {
        $name = preg_replace('#\{\{(.*?)\}\}#', '${1}', $name);
        $name = str_replace("]", "", str_replace("[", ".", $name));

        $parts = explode(".", $name);
        $newName = "formElement";
        foreach ($parts as $_part) {
            $_part = trim($_part);
            if (is_numeric($_part)) {
                $newName .= '[' . $_part . ']';
                continue;
            }

            if (is_numeric(substr($_part, 0, 1))) {
                $_part = "ph" . $_part;
            }
            $newName .= '.' . $_part;
        }

        return $newName;
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

    /**
     * @param string $id
     * @param string $html
     * @return $this
     */
    public function addHtmlTemplate($id, $html)
    {
        $this->htmlTemplates[$id] = $html;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlTemplates()
    {
        $templates = [];

        foreach ($this->htmlTemplates as $id => $html) {
            $templates[] = sprintf('<script id="%s" type="text/ng-template">%s</script>', $id, $html);
        }

        return implode(PHP_EOL, $templates);
    }

    /**
     * @param string $option
     * @return string
     */
    public function getWhitelabelOption($option)
    {
        if (empty($this->config['whitelabel'][$option])) {
            return "";
        }

        return $this->config['whitelabel'][$option];
    }
}
