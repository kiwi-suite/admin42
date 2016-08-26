<?php
namespace Admin42\Permission\Service;

use Admin42\Authentication\AuthenticationService;
use Admin42\Model\User;
use Core42\Permission\Permission;
use Core42\Permission\Role;
use Core42\Permission\Service\AssertionPluginManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Stdlib\ArrayUtils;

class PermissionFactory implements FactoryInterface
{
    const GUEST_ROLE = 'guest';
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AuthenticationService $authService */
        $authService = $container->get(AuthenticationService::class);

        $identity = new User();
        $identity->setRole(self::GUEST_ROLE);
        if ($authService->hasIdentity()) {
            $identity = $authService->getIdentity();
        }

        $permissionService = new Permission(
            $container->get(AssertionPluginManager::class),
            $identity,
            self::GUEST_ROLE
        );

        $config = $container->get('config')['permissions']['permission_service']['admin42'];

        $roles = [];
        foreach ($config as $roleName => $roleOptions) {
            $options = (!empty($roleOptions['options'])) ? $roleOptions['options'] : [];
            $roleObject = new Role($roleName, $options);
            $permissions = (!empty($roleOptions['permissions'])) ? $roleOptions['permissions'] : [];
            foreach ($permissions as $permission) {
                $roleObject->addPermission($permission);
            }
            $roles[$roleName] = $roleObject;
        }

        foreach ($config as $roleName => $roleOptions) {
            if (!empty($roleOptions['inherit_from']) && isset($roles[$roleOptions['inherit_from']])) {
                $options = $roles[$roleName]->getOptions();
                $options = ArrayUtils::merge($options, $roles[$roleOptions['inherit_from']]->getOptions());
                $roles[$roleName]->setOptions($options);
                $roles[$roleName]->addChild($roles[$roleOptions['inherit_from']]);
            }
            $permissionService->addRole($roles[$roleName]);
        }


        return $permissionService;
    }
}
