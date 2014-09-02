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
use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\View\Renderer\PhpRenderer;

class SendCommand extends AbstractCommand
{
    /**
     * @var Message
     */
    private $mailMessage;

    /**
     * @var MailModel
     */
    private $layout;

    /**
     * @var MailModel
     */
    private $body;

    /**
     * @var array
     */
    private $parts = array();

    /**
     * @var string
     */
    private $subject;

    /**
     *
     */
    protected function init()
    {
        $this->mailMessage = new Message();

        $this->layout = new MailModel();
        $this->layout->setHtmlTemplate("mail/admin42/layout.html.phtml");
        $this->layout->setPlainTemplate("mail/admin42/layout.plain.phtml");

        $this->parts = array(
            'plain' => array(
                'type' => Mime::TYPE_TEXT,
            ),
            'html' => array(
                'type' => Mime::TYPE_HTML,
            ),
        );
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

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
     * @param MailModel $body
     * @return $this
     */
    public function setBody(MailModel $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!($this->body instanceof MailModel)) {
            $this->addError("body", "invalid body");

            return;
        }

        $config = $this->getServiceManager()->get('config');
        $config = $config['project'];

        $this->body->setVariables(array(
            'projectBaseUrl' => $config['project_base_url'],
            'projectName' => $config['project_name'],
        ));

        $this->layout->setVariables(array(
            'projectBaseUrl' => $config['project_base_url'],
            'projectName' => $config['project_name'],
        ));


        $this->subject = $config['email_subject_prefix'] . $this->subject;
        $this->mailMessage->setSubject($this->subject);

        if ($this->mailMessage->getFrom()->count() == 0) {
            $this->mailMessage->addFrom($config['email_from']);
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function execute()
    {
        $viewResolver = $this->getServiceManager()->get('ViewResolver');

        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($viewResolver);

        $body = new \Zend\Mime\Message();

        foreach ($this->parts as $type => $options) {
            if (!$this->body->hasTemplate($type)) {
                continue;
            }
            $this->body->useTemplate($type);
            $this->layout->useTemplate($type);
            $this->layout->setVariable('content', $phpRenderer->render($this->body));

            $part = new Part($phpRenderer->render($this->layout));
            $part->type = $options['type'];
            $part->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
            $part->charset = "UTF-8";
            $body->addPart($part);
        }

        if (count($body->getParts()) == 0) {
            return;
        }


        $this->mailMessage->setBody($body);
        $this->mailMessage->setEncoding('UTF-8');
        $this->mailMessage
            ->getHeaders()
            ->get('content-type')
            ->setType(Mime::MULTIPART_ALTERNATIVE);

        $transport = $this->getServiceManager()->get('Core42\Mail\Transport');
        $transport->send($this->mailMessage);
    }
}
