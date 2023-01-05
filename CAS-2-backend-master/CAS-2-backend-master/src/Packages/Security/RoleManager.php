<?php

namespace App\Packages\Security;

use App\Entity\Security\Role;
use App\Repository\Security\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Class RoleMerger.
 */
class RoleManager
{
    const MERGE_STRATEGY_EXCLUSION = 0;

    /** @var RoleHierarchyInterface */
    private RoleHierarchyInterface $roleHierarchy;

    /** @var RoleRepository */
    private RoleRepository $roleRepository;

    /** @var Role[] */
    private array $roleLoadedFromDatabase;

    /**
     * RoleManager constructor.
     * @param RoleHierarchyInterface $roleHierarchy
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy, RoleRepository $roleRepository)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->roleRepository = $roleRepository;

        foreach ($this->roleRepository->findBy(['deleted' => false]) as $roleFromDatabase) {
            $this->roleLoadedFromDatabase[RoleManager::formatMask($roleFromDatabase->getCode())] = $roleFromDatabase;
        }
    }

    /**
     * @param ArrayCollection $firstRoleCollection
     * @param ArrayCollection $secondRoleCollection
     * @param $strategy
     *
     * @return ArrayCollection
     */
    public function merge(ArrayCollection $firstRoleCollection, ArrayCollection $secondRoleCollection, $strategy = self::MERGE_STRATEGY_EXCLUSION): ArrayCollection
    {
        switch ($strategy) {
            case self::MERGE_STRATEGY_EXCLUSION:
                $mergedRolesCollection = $this->mergeByExclusionStrategy($firstRoleCollection, $secondRoleCollection);
                break;

            default:
                throw new \LogicException(sprintf('Not found %s strategy', $strategy));
        }

        return $mergedRolesCollection;
    }

    private function getHashTree(Role $role, $includeRoleDetailed = false): string
    {
        $masks = [];

        if ($includeRoleDetailed) {
            $masks[] = $role->getCode();
        }

        while (null !== $role->getParent()) {
            $role = $role->getParent();
            $masks[] = $role->getCode();
        }

        return sha1(implode('.', $masks));
    }

    private function findRolesByCodes(array $codes): array
    {
        $roles = [];
        foreach ($codes as $k => $code) {
            if (isset($this->roleLoadedFromDatabase[RoleManager::formatMask($code)])) {
                $roles[] = $this->roleLoadedFromDatabase[RoleManager::formatMask($code)];
                unset($codes[$k]);
            }
        }

        if (!$codes) {
            return $roles;
        }

        /** @var Role $rolesFromDatabase */
        $rolesFromDatabase = $this->roleRepository
            ->createQueryBuilder('r')
            ->where('r.code IN (:codes)')
            ->setParameter('codes', $codes)
            ->getQuery()
            ->getResult();

        foreach ($rolesFromDatabase as $roleFromDatabase) {
            $roles[] = $roleFromDatabase;
            $this->roleLoadedFromDatabase[RoleManager::formatMask($roleFromDatabase->getCode())] = $roleFromDatabase;
        }

        return $roles;
    }

    /**
     * @param ArrayCollection $firstRoleCollection
     * @param ArrayCollection $secondRoleCollection
     *
     * @return ArrayCollection
     */
    private function mergeByExclusionStrategy(ArrayCollection $firstRoleCollection, ArrayCollection $secondRoleCollection): ArrayCollection
    {
        if ($firstRoleCollection->isEmpty() || $secondRoleCollection->isEmpty()) {
            return new ArrayCollection([]);
        }

        $mergingResult = [];

        $linearListRolesFirst = $this->roleHierarchy->getReachableRoleNames($firstRoleCollection->map(function(Role $role) {
            return RoleManager::formatMask($role->getCode());
        })->getValues());

        $linearListRolesSecond = $this->roleHierarchy->getReachableRoleNames($secondRoleCollection->map(function(Role $role) {
            return RoleManager::formatMask($role->getCode());
        })->getValues());

        foreach ($linearListRolesFirst as $linearListRoleFirst) {
            if (in_array($linearListRoleFirst, $linearListRolesSecond)) {
                $mergingResult[] = RoleManager::formatMask($linearListRoleFirst);
            }
        }

        foreach ($linearListRolesSecond as $linearListRoleSecond) {
            if (in_array($linearListRoleSecond, $linearListRolesFirst)) {
                $mergingResult[] = RoleManager::formatMask($linearListRoleSecond);
            }
        }

        $mergingResult = array_unique($mergingResult);

        return new ArrayCollection($this->findRolesByCodes($mergingResult));
    }

    /**
     * @param ArrayCollection|Role[] $roleCollection
     * @param Role $role
     *
     * @return bool
     */
    public function isGranted($roleCollection, Role $role): bool
    {
        return in_array($role, $this->fetchLinearRoles($roleCollection), true);
    }

    /**
     * @param ArrayCollection|Role[] $roleCollection
     *
     * @return Role[]
     */
    public function fetchLinearRoles($roleCollection): array
    {
        $rolesCodes = $roleCollection->map(function(Role $role) { return $role->getCode(); })->getValues();
        return $this->findRolesByCodes($rolesCodes);
    }

    /**
     * @param string $role
     *
     * @return string
     */
    public static function formatMask(string $role): string
    {
        $role = strtoupper($role);

        if (strlen($role) < 5 || 'ROLE_' !== mb_substr($role, 0, 5)) {
            $role = 'ROLE_'.$role;
        }

        return $role;
    }

    /**
     * @param Role[] $roles
     *
     * @return array
     */
    public function buildRolesTree($roles): array
    {
        $hierarchy = [];

        foreach ($roles as $role) {
            $roleCode = $role->getCode();

            if ($role->getParent()) {
                $parentCode = $role->getParent()->getCode();

                if (!isset($hierarchy[RoleManager::formatMask($parentCode)])) {
                    $hierarchy[RoleManager::formatMask($parentCode)] = [];
                }
                $hierarchy[RoleManager::formatMask($parentCode)][] = RoleManager::formatMask($roleCode);
            } else {
                if (!isset($hierarchy[RoleManager::formatMask($roleCode)])) {
                    $hierarchy[RoleManager::formatMask($roleCode)] = [];
                }
            }
        }

        return $hierarchy;
    }
}
