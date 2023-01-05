<?php

namespace App\Interfaces;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


interface SimpleAuthenticatorInterface
{
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey);

    public function supportsToken(TokenInterface $token, $providerKey);
}
