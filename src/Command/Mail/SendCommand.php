<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\Mail;

use Core42\Command\AbstractCommand;
use Core42\View\Model\MailModel;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\View\Renderer\PhpRenderer;

class SendCommand extends \Core42\Command\Mail\SendCommand
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this->getServiceManager()->get(TranslatorInterface::class)->setLocale('en-US');

        $this->layout = new MailModel();
        $this->layout->setHtmlTemplate("mail/admin42/layout.html.phtml");
        $this->layout->setPlainTemplate("mail/admin42/layout.plain.phtml");
    }
}
