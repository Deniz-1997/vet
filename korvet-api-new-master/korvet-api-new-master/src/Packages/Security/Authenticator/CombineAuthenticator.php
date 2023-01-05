<?php

namespace App\Packages\Security\Authenticator;

use App\Traits\RoleResolver;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Interfaces\SimplePreAuthenticatorInterface;

/**
 * Class CombineAuthenticator
 */
class CombineAuthenticator implements SimplePreAuthenticatorInterface
{
    use RoleResolver;

    /** @var SimplePreAuthenticatorInterface[] */
    private array $authenticators = [];

    /**
     * CombineAuthenticator constructor.
     * @param SimplePreAuthenticatorInterface ...$authenticators
     */
    public function __construct(SimplePreAuthenticatorInterface ...$authenticators)
    {
        $this->authenticators = $authenticators;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey): PreAuthenticatedToken
    {
        if (!$request->headers->get('Authorization') && !$request->cookies->get('Authorization')) {
            throw new BadCredentialsException();
        }

        $apiKey = $request->headers->has('Authorization') ? $request->headers->get('Authorization') : $request->cookies->get('Authorization');
        $authHeader = $request->headers->get("Authorization");

        if ($request->cookies->get("Authorization")) {
            $apiKey = str_replace("Access-Token: ", "", $request->cookies->get("Authorization"));
        } elseif(isset($authHeader)) {
            if (strpos($request->getPathInfo(), "/api/") === 0) {
                $apiKey = str_replace("Access-Token: ", "", $authHeader);
            }
        }

        return new PreAuthenticatedToken(
            'anon.',
            $providerKey,
            $this->resolveDefaultRoles($request)
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey): bool
    {
        return true;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey): PreAuthenticatedToken
    {
        foreach ($this->authenticators as $authenticator) {
            if ($authenticator->supportsToken($token, $providerKey)) {
                try {
                    /** @var PreAuthenticatedToken $token */
                    $token = $authenticator->authenticateToken($token, $userProvider, $providerKey);
                    if ($token instanceof PreAuthenticatedToken) {
                        $token->setAuthenticated(true);
                    }

                    return $token;
                } catch (Exception $exception){
                    continue;
                }
            }
        }
    }
}
