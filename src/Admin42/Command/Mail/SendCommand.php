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
use Zend\Mail\Header\ContentType;
use Zend\Mail\Message;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

class SendCommand extends AbstractCommand
{
    /**
     * @var Message
     */
    private $mailMessage;

    /**
     * @var ViewModel
     */
    private $layout;

    /**
     * @var ViewModel|string
     */
    private $bodyHtml;

    /**
     * @var ViewModel|string
     */
    private $bodyPlain;

    /**
     *
     */
    protected function init()
    {
        $this->mailMessage = new Message();

        $this->layout = new ViewModel();
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->mailMessage->setSubject($subject);

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addFrom($email, $name = null)
    {
        $this->mailMessage->addFrom($email, $name);

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        $this->mailMessage->addTo($email, $name);

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        $this->mailMessage->addCc($email, $name);

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        $this->mailMessage->addBcc($email, $name);

        return $this;
    }

    /**
     * @param string $email
     * @param null|string $name
     * @return $this
     */
    public function addReplyTo($email, $name = null)
    {
        $this->mailMessage->addReplyTo($email, $name);

        return $this;
    }

    /**
     * @param ViewModel|string $bodyHtml
     * @return $this
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;

        return $this;
    }

    /**
     * @param ViewModel|string $bodyPlain
     * @return $this
     */
    public function setBodyPlain($bodyPlain)
    {
        $this->bodyPlain = $bodyPlain;

        return $this;
    }

    protected function execute()
    {
        $viewResolver = $this->getServiceManager()->get('ViewResolver');

        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($viewResolver);

        $parts = array();

        if ($this->bodyHtml !== null) {
            if ($this->bodyHtml instanceof ViewModel) {
                $this->bodyHtml = $phpRenderer->render($this->bodyHtml);
            }
            $this->layout->setVariable('content', $this->bodyHtml);

            $this->layout->setTemplate('mail/admin42/layout.html.phtml');

            $htmlPart = new Part($phpRenderer->render($this->layout));
            $htmlPart->type = 'text/html';
            $htmlPart->charset = 'UFT-8';
            $htmlPart->encoding = \Zend\Mime\Mime::ENCODING_QUOTEDPRINTABLE;
            $parts[] = $htmlPart;
            $this->layout->clearVariables();
        }

        if ($this->bodyPlain !== null) {
            if ($this->bodyPlain instanceof ViewModel) {
                $this->bodyPlain = $phpRenderer->render($this->bodyPlain);
            }
            $this->layout->setVariable('content', $this->bodyPlain);

            $this->layout->setTemplate('mail/admin42/layout.plain.phtml');

            $plainPart = new Part($phpRenderer->render($this->layout));
            $plainPart->type = 'text/plain';
            $parts[] = $plainPart;
            $this->layout->clearVariables();
        }

        if (empty($parts)) {
            return;
        }

        $parts = array_reverse($parts);

        $body = new \Zend\Mime\Message();
        $body->setParts($parts);

        $this->mailMessage->setBody($body);

        $this->mailMessage->getHeaders()->get('content-type')->setType('multipart/alternative');

        $transport = $this->getServiceManager()->get('Core42\Mail\Transport');
        $transport->send($this->mailMessage);
    }
}
