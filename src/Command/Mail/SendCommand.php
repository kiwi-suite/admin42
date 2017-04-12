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
