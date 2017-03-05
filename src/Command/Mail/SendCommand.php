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

use Core42\View\Model\MailModel;
use Zend\I18n\Translator\TranslatorInterface;

class SendCommand extends \Core42\Command\Mail\SendCommand
{
    /**
     *
     */
    protected function init()
    {
        $this->getServiceManager()->get(TranslatorInterface::class)->setLocale('en-US');

        $this->layout = new MailModel();
        $this->layout->setHtmlTemplate('mail/admin42/layout.html.phtml');
        $this->layout->setPlainTemplate('mail/admin42/layout.plain.phtml');
    }
}
