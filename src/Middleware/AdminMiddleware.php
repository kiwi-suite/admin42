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


namespace Admin42\Middleware;

use Admin42\Authentication\AuthenticationService;
use Core42\Hydrator\BaseHydrator;
use Core42\Model\GenericModel;
use Core42\Permission\PermissionInterface;
use Core42\Permission\Service\PermissionPluginManager;
use Core42\Stdlib\DefaultGetterTrait;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Form\FieldsetInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminMiddleware
{
    use DefaultGetterTrait;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return RedirectResponse|null
     */
    public function __invoke(Request $request, Response $response)
    {
        /** @var MvcEvent $mvcEvent */
        $mvcEvent = $this->getServiceManager()->get('Application')->getMvcEvent();

        /** @var PermissionInterface $permission */
        $permission = $this->getServiceManager()->get(PermissionPluginManager::class)->get('admin42');

        $routeName = 'route/' . $mvcEvent->getRouteMatch()->getMatchedRouteName();

        $hasIdentity = $this->serviceManager->get(AuthenticationService::class)->hasIdentity();

        if (!$permission->authorized($routeName)) {
            $router = $this->getServiceManager()->get('Router');
            $url = $router->assemble([], ['name' => 'admin/permission-denied']);
            if (!$hasIdentity) {
                $url = $router->assemble([], ['name' => 'admin/login']);
            }

            return new RedirectResponse($url);
        }

        $assetsConfig = $this->getServiceManager()->get('config')['admin']['assets'];
        foreach ($assetsConfig as $namespace => $spec) {
            $this->setupStylesheets(($spec['css']) ? $spec['css'] : [], $namespace);
            $this->setupJavascript(($spec['js']) ? $spec['js'] : [], $namespace);
        }

        $this->getServiceManager()->get('FormElementManager')->addInitializer(function ($container, $element) {
            if (\method_exists($element, 'setHydratorPrototype')) {
                $element->setHydratorPrototype($container->get('HydratorManager')->get(BaseHydrator::class));
            }
            if ($element instanceof FieldsetInterface) {
                $element->setObject(new GenericModel());
            }
        });

        $locale = 'en-US';
        if ($hasIdentity) {
            $locale = $this->serviceManager->get(AuthenticationService::class)->getIdentity()->getLocale();
        }
        $this->getServiceManager()->get(TranslatorInterface::class)->setLocale($locale);
    }

    /**
     * @param array $stylesheets
     * @param $namespace
     */
    protected function setupStylesheets(array $stylesheets, $namespace)
    {
        $headLink = $this->getServiceManager()->get('ViewHelperManager')->get('headLink');
        $assetUrl = $this->getServiceManager()->get('ViewHelperManager')->get('assetUrl');

        foreach ($stylesheets as $css) {
            $headLink->appendStylesheet($assetUrl($css, $namespace));
        }
    }

    /**
     * @param array $javaScripts
     * @param $namespace
     */
    protected function setupJavascript(array $javaScripts, $namespace)
    {
        $headScript = $this->getServiceManager()->get('ViewHelperManager')->get('headScript');
        $assetUrl = $this->getServiceManager()->get('ViewHelperManager')->get('assetUrl');

        foreach ($javaScripts as $js) {
            $headScript->appendFile($assetUrl($js, $namespace));
        }
    }
}
