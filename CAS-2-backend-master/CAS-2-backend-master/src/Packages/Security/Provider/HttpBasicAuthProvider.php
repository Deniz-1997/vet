<?php

namespace App\Packages\Security\Provider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Model\User;

/**
 * OAuthTokenProvider.
 */
class HttpBasicAuthProvider implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): UserInterface
    {
        throw new \LogicException('This provider usage for OAuth server integration. Request to OAuth server locate into Authenticator class.');
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
        return $class === User::class || is_subclass_of($class, User::class);
    }
}
