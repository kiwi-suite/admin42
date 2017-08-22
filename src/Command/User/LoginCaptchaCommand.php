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


namespace Admin42\Command\User;

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\User;
use Admin42\TableGateway\UserTableGateway;
use GuzzleHttp\Client;
use Zend\Session\Container;

class LoginCaptchaCommand extends LoginCommand
{
    /**
     * @var string
     */
    protected $captcha;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @param string $captcha
     * @return $this
     */
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;

        return $this;
    }

    protected function preExecute()
    {
        $this->authenticationService = $this->getServiceManager()->get(AuthenticationService::class);

        $config = $this->getServiceManager()->get('Config');

        if (empty($this->captcha)) {
            $this->addError('identity', 'login.error.failure');
            return;
        }

        if (!$this->checkCaptcha($this->captcha, $config['admin']['login_captcha_options']['secret'])) {
            $this->addError('identity', 'login.error.failure');
            return;
        }

        $container = new Container('login');
        if ($container->success === true) {
            $this->user = $this->getTableGateway(UserTableGateway::class)->selectByPrimary($container->user_id);
        } else {
            $this->addError('identity', 'login.error.failure');
        }
        $container->getManager()->getStorage()->clear('login');
    }

    /**
     * @param string $captcha
     * @param string $secret
     * @return bool
     */
    protected function checkCaptcha($captcha, $secret)
    {
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', ['verify' => false, 'form_params' => [
            'secret' => $secret,
            'response' => $captcha,
            'remoteip' => $this->ip,
        ]]);

        if ($response->getStatusCode() == 200) {
            $responseData = @\json_decode($response->getBody()->getContents(), true);
            if ($responseData !== false && $responseData['success']) {
                return true;
            }
        }

        return false;
    }
}
