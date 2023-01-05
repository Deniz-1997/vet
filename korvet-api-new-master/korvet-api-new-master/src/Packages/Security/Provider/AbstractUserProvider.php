<?php

namespace App\Packages\Security\Provider;

use App\Entity\User\User;
use App\Repository\OAuth\AccessTokenRepository;
use App\Repository\User\UserRepository;
use App\Packages\Security\RoleManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * AbstractUserProvider.
 */
class AbstractUserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var AccessTokenRepository
     */
    private AccessTokenRepository $accessTokenRepository;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @var RoleManager
     */
    private RoleManager $roleManager;

    private EntityManagerInterface $entityManager;

    /**
     * @param UserRepository        $userRepository
     * @param AccessTokenRepository $accessTokenRepository
     * @param RequestStack          $requestStack
     * @param RoleManager           $roleManager
     * @param EntityManagerInterface          $entityManager
     */
    public function __construct(UserRepository $userRepository, AccessTokenRepository $accessTokenRepository, 
                    RequestStack $requestStack, RoleManager $roleManager, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->requestStack = $requestStack;
        $this->roleManager = $roleManager;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $totalRoles = [];

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['username' => $username, 'deleted' => false]);
        if (!$user) {
            throw new AuthenticationException();
        }
        foreach ($user->getGroups() as $group) {
            foreach ($group->getRoles() as $role) {
                array_push($totalRoles, $role->getCode());
            }
        }
        if (count($totalRoles) > 0) {
            $user->setRoles($totalRoles);
        }
        $this->accessToken = $this->accessTokenRepository->findOneBy(['user' => $user]);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return UserInterface::class === $class;
    }
}
