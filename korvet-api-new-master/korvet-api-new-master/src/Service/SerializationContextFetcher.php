<?php

namespace App\Service;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Class SerializationContextFetcher
 */
class SerializationContextFetcher
{
    /**
     * @var RoleHierarchyInterface
     */
    private RoleHierarchyInterface $roleHierarchy;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var Reader
     */
    private Reader $reader;

    /**
     * @var string[]
     */
    private array $systemRoles = [];

    /**
     * @var string[]
     */
    private array $reachableRoles = [];

    /**
     * @var array
     */
    private array $cache = [];

    /**
     * SerializationContextFetcher constructor.
     *
     * @param RoleHierarchyInterface $roleHierarchy
     * @param TokenStorageInterface  $tokenStorage
     * @param Reader                 $reader
     * @param array $roles
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy, TokenStorageInterface $tokenStorage, Reader $reader, $roles)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
        $this->systemRoles = $this->flatArray($roles);

        if (!method_exists($this->roleHierarchy, 'getReachableRoleNames')) {
            if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getRoleNames()) {
                foreach ($this->roleHierarchy->getReachableRoleNames($this->tokenStorage->getToken()->getRoleNames()) as $role) {
                    $roleString = str_replace('role_', '', mb_strtolower($role));
                    $roleString = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $roleString))));

                    $this->reachableRoles[] = $roleString;
                }
            }
        } else {
            if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getRoleNames()) {
                foreach ($this->roleHierarchy->getReachableRoleNames($this->tokenStorage->getToken()->getRoleNames()) as $role) {
                    $roleString = str_replace('role_', '', mb_strtolower($role));
                    $roleString = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $roleString))));

                    $this->reachableRoles[] = $roleString;
                }
            }
        }
    }

    /**
     * @param string $operation
     * @param string $entityClass
     * @return string[]
     */
    public function getSerializationGroups(string $operation, string $entityClass): array
    {
        if (isset($this->cache['getSerializationGroups_' . $entityClass . $operation])) {
            return $this->cache['getSerializationGroups_' . $entityClass . $operation];
        }

        $roles = $this->reachableRoles;
        if (array_search('root', $roles) !== false) {
            foreach ($this->systemRoles as $systemRole) {
                $roleString = str_replace('role_', '', mb_strtolower($systemRole));
                $roleString = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $roleString))));

                if (array_search($roleString, $roles) === false) {
                    $roles[] = $roleString;
                }
            }
        }

        $groups = ['default', $operation];
        foreach ($roles as $role) {
            $groups[] = 'permission.' . $operation . '.' . $role;
            $groups[] = 'permission.' . $role;
        }

        //        /** @var SerializationContext $serializationContextAnnotation */
        //        $serializationContextAnnotation = $this->reader->getClassAnnotation(new \ReflectionClass($entityClass), SerializationContext::class);
        //        if ($serializationContextAnnotation) {
        //            $code = $serializationContextAnnotation->code;
        //        } else {
        //
        //        }

        $data = explode('\\', $entityClass);
        $className = array_pop($data);
        $code = strtolower($className);

        $groups[] = sprintf('%s.%s', $code, $operation);
        $groups[] = sprintf('%s.%s', $code, 'default');

        $this->cache['getSerializationGroups_' . $entityClass . $operation] = $groups;

        return $groups;
    }

    /**
     * @param array $data
     * @return array
     */
    private function flatArray(array $data): array
    {
        $result = array();
        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) === 'ROLE') {
                $result[$key] = $key;
            }
            if (is_array($value)) {
                $tmp = $this->flatArray($value);
                if (count($tmp) > 0) {
                    $result = array_merge($result, $tmp);
                }
            } else {
                $result[$value] = $value;
            }
        }
        return array_unique($result);
    }
}
