<?php

namespace App\Packages\Security\Authentication;

use App\Entity\OAuth\AccessToken;
use App\Entity\OAuth\RefreshToken;
use App\Entity\Security\ClientGroup;
use App\Entity\Security\Group;
use App\Entity\User\User;
use App\Repository\OAuth\AccessTokenRepository;
use App\Packages\Security\RoleManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Class OAuthProvider.
 */
class OAuthProvider extends \FOS\OAuthServerBundle\Security\Authentication\Provider\OAuthProvider
{
    /** @var AccessTokenRepository */
    private AccessTokenRepository $accessTokenRepository;

    /** @var RoleManager */
    private RoleManager $roleManager;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * @required
     *
     * @param AccessTokenRepository $accessTokenRepository
     * @param RoleManager $roleManager
     * @param EntityManagerInterface $entityManager
     */
    public function setAccessTokenRepository(AccessTokenRepository $accessTokenRepository, RoleManager $roleManager, EntityManagerInterface $entityManager): void
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->roleManager = $roleManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param TokenInterface $token
     *
     * @return OAuthToken|TokenInterface
     */
    public function authenticate(TokenInterface $token)
    {
        $oldToken = parent::authenticate($token);

        $userRoleCollection = new ArrayCollection();
        $clientRoleCollection = new ArrayCollection();
        $user = $oldToken->getUser();

        if (is_object($user) && ($user->isStatus() === false || $user->isDeleted() === true)) {
            $this->entityManager->getRepository(RefreshToken::class)->removeTokensByUser($user);
            $this->entityManager->getRepository(AccessToken::class)->removeTokensByUser($user);
            return $oldToken;
        }

        $userGroups = ($user instanceof User) ? $user->getGroups() : [];

        $accessToken = $this->accessTokenRepository->findOneBy(['token' => $oldToken->getToken()]);

        $clientGroups = ($accessToken instanceof AccessToken) ? $accessToken->getClient()->getGroups() : [];

        /** @var Group $userGroup */
        foreach ($userGroups as $userGroup) {
            foreach ($userGroup->getRoles() as $userRole) {
                $userRoleCollection->add($userRole);
            }
        }

        /** @var ClientGroup $clientGroup */
        foreach ($clientGroups as $clientGroup) {
            foreach ($clientGroup->getRoles() as $clientRole) {
                $clientRoleCollection->add($clientRole);
            }
        }

        $totalRoles = ($user instanceof UserInterface) ? $this->roleManager->merge($userRoleCollection, $clientRoleCollection, RoleManager::MERGE_STRATEGY_EXCLUSION) :
            $this->roleManager->fetchLinearRoles($clientRoleCollection);

        $arrayRoles = [];

        foreach ($totalRoles as $role) {
            $arrayRoles[] = RoleManager::formatMask($role->getCode());
        }

        $newToken = new OAuthToken($arrayRoles);

        if ($oldToken->getUser() instanceof UserInterface) {
            $newToken->setUser($oldToken->getUser());
        } else {
            $newToken->setUser('Application_' . $accessToken->getClient()->getPublicId());
        }

        $newToken->setAttributes($oldToken->getAttributes());
        $newToken->setAuthenticated($oldToken->isAuthenticated());
        $newToken->setToken($oldToken->getToken());

        return $newToken;
    }
}
