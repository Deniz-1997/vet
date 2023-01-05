<?php

namespace App\Packages\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use App\Packages\Security\Authenticator\EncodedHeaderUserAuthenticator;


/**
 * Class SecurityAuthenticator
 */
class SecurityAuthenticator implements SimplePreAuthenticatorInterface
{
    /** @var SimplePreAuthenticatorInterface[] */
    private array $authenticators = [];

    /**
     * SecurityAuthenticator constructor.
     *
     * @param EncodedHeaderUserAuthenticator $encodedHeaderUserAuthenticator
     * @param OAuthProvider                  $oauthProvider
     */
    public function __construct(EncodedHeaderUserAuthenticator $encodedHeaderUserAuthenticator, OAuthProvider $oauthProvider)
    {
        $this->authenticators[] = $encodedHeaderUserAuthenticator;
        $this->authenticators[] = $oauthProvider;
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

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
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
            if ($authenticator instanceof AuthenticationProviderInterface) {
                if ($authenticator->supports($token)) {
                    try {
                        return $authenticator->authenticate($token, $userProvider, $providerKey);
                    } catch (\Exception $exception){
                        continue;
                    }
                }
            } else {
                if ($authenticator->supportsToken($token, $providerKey)) {
                    try {
                        return $authenticator->authenticateToken($token, $userProvider, $providerKey);
                    } catch (\Exception $exception){
                        continue;
                    }
                }
            }
        }
    }
}
