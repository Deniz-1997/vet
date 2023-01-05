<?php

namespace App\Packages\Security\Provider;

use App\Packages\Client\AccountClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Model\User;

/**
 * OAuthTokenProvider.
 */
class OAuthTokenProvider implements UserProviderInterface
{
    /** @var AccountClient */
    private AccountClient $accountClient;

    /** @var RequestStack */
    private RequestStack $requestStack;

    /** @var string */
    private string $url;

    /**
     * OAuthTokenProvider constructor.
     *
     * @param AccountClient $accountClient
     * @param RequestStack $requestStack
     * @param string $url
     */
    public function __construct(AccountClient $accountClient, RequestStack $requestStack, string $url)
    {
        $this->accountClient = $accountClient;
        $this->requestStack = $requestStack;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            return $this->accountClient->getCurrentAuthenticationInfo($username);
        } catch (\Exception $e) {
            throw new UsernameNotFoundException('Could not retrieve user information.');
        }
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
