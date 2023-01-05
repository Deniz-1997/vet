<?php

namespace App\Packages\Security;

use App\Repository\Security\RoleRepository;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class DoctrineRoleHierarchyLoader extends RoleHierarchy
{
    /**
     * @var array
     */
    private array $hierarchy;

    /**
     * DoctrineRoleHierarchyLoader constructor.
     *
     * @param array          $hierarchy
     * @param RoleRepository $roleRepository
     * @param RoleManager    $roleManager
     */
    public function __construct(array $hierarchy, RoleRepository $roleRepository, RoleManager $roleManager)
    {
        $roles = $roleRepository->findAll();
        $newHierarchy = $roleManager->buildRolesTree($roles);

        $this->hierarchy = $hierarchy;
        parent::__construct($hierarchy);
    }

    /**
     * @return array
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
    }
}
