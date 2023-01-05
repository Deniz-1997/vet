<?php

namespace App\Packages\Security\Provider;

use App\Entity\Security\Group;
use App\Entity\User\User;
use App\Repository\OAuth\AccessTokenRepository;
use App\Repository\User\UserRepository;
use App\Packages\Security\RoleManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\Security\Role;
use App\Interfaces\CAS\CasAuthInterface;
use OAuth2\OAuth2ServerException;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @var CasAuthInterface
     */
    private CasAuthInterface $casAuthInterface;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @param UserRepository        $userRepository
     * @param AccessTokenRepository $accessTokenRepository
     * @param RequestStack          $requestStack
     * @param RoleManager           $roleManager
     */
    public function __construct(
        UserRepository $userRepository,
        AccessTokenRepository $accessTokenRepository,
        RequestStack $requestStack,
        RoleManager $roleManager,
        CasAuthInterface $casAuthInterface,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->requestStack = $requestStack;
        $this->roleManager = $roleManager;
        $this->casAuthInterface = $casAuthInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->passwordEncoder = $passwordEncoder;
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
            $user = $this->userRepository->findOneBy(['email' => $username, 'deleted' => false]);
        }
        if (!$user) {
            $user = $this->userRepository->findOneBy(['phoneNumber' => $username, 'deleted' => false]);
        }
        if (!$user) {
            $user = $this->casAuthInterface->loadCasUser($this->requestStack->getCurrentRequest()->get('username'), $this->requestStack->getCurrentRequest()->get('password'));
            if (!$user)
                throw new OAuth2ServerException(Response::HTTP_BAD_REQUEST, 'user_invalid', "Invalid username and password combination");
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
