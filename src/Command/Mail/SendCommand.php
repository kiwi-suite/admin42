<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\Command\Mail;

use Zend\I18n\Translator\TranslatorInterface;

class SendCommand extends \Core42\Command\Mail\SendCommand
{
    /**
     *
     */
    protected function init()
    {
        parent::init();
        $this->getServiceManager()->get(TranslatorInterface::class)->setLocale('en-US');
    }

    /**
     *
     */
    protected function configure()
    {
        $this->mailMessage = \Swift_Message::newInstance();

        $this->parts = [
            'plain' => [
                'type' => 'text/plain',
            ],
            'html' => [
                'type' => 'text/html',
            ],
        ];

        $config = $this->getServiceManager()->get("config")['project'];

        $adminConfig = $this->getServiceManager()->get("config")['admin']['email'];
        foreach ($adminConfig as $key => $value) {
            if ($value === null) {
                continue;
            }
            $config[$key] = $value;
        }

        $this->mailModel->normalizeData(
            $config,
            $this->enableProjectDefaults,
            $this->enableSubjectPrefix
        );
    }
}
