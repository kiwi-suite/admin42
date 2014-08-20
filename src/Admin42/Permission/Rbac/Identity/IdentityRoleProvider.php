<?php
namespace Admin42\Permission\Rbac\Identity;

use Admin42\Authentication\AuthenticationService;
use Core42\Permission\Rbac\Identity\IdentityRoleProviderInterface;
use Core42\Permission\Rbac\Role\RoleInterface;

class IdentityRoleProvider implements IdentityRoleProviderInterface
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return null|string[]|RoleInterface[]
     */
    public function getRoles()
    {
        if (!$this->authenticationService->hasIdentity()) {
            return null;
        }

        return array($this->authenticationService->getIdentity()->getRole());
    }
}
