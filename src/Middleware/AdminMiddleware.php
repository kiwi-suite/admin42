<?php
namespace Admin42\Middleware;

use Admin42\Authentication\AuthenticationService;
use Core42\Permission\PermissionInterface;
use Core42\Permission\Service\PermissionPluginManager;
use Core42\Stdlib\DefaultGetterTrait;
use Zend\Diactoros\Response\RedirectResponse;
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

        if (!$permission->isGranted($routeName)) {
            $router = $this->getServiceManager()->get('Router');
            $hasIdentity = $this->serviceManager->get(AuthenticationService::class)->hasIdentity();
            $url = $router->assemble([], ['name' => 'admin/permission-denied']);
            if (!$hasIdentity) {
                $url = $router->assemble([], ['name' => 'admin/login']);
            }
            return new RedirectResponse($url);
        }
    }
}
