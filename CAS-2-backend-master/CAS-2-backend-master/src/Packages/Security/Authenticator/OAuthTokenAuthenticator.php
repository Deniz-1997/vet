<?php

namespace App\Packages\Security\Authenticator;

use App\Traits\RoleResolver;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Model\AuthenticationInformation;
use App\Packages\Security\Provider\OAuthTokenProvider;
use App\Service\Trace\Tracer;

/**
 * Class ApiKeyAuthenticator
 */
class OAuthTokenAuthenticator implements SimplePreAuthenticatorInterface
{
    use RoleResolver;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /**
     * ApiKeyAuthenticator constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey): PreAuthenticatedToken
    {
        if (!$apiKey = $request->headers->get('Authorization')) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
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
        return $token instanceof PreAuthenticatedToken;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey): PreAuthenticatedToken
    {
        if ($userProvider instanceof ChainUserProvider) {
            foreach ($userProvider->getProviders() as $provider) {
                if ($provider instanceof OAuthTokenProvider) {
                    $userProvider = $provider;
                    break;
                }
            }
        }

        if (!$userProvider instanceof OAuthTokenProvider) {
            throw new InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of OAuthTokenProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();

        Tracer::getInstance()->createSpan('authorization', 'Authorization request', 'CLIENT');
        /** @var AuthenticationInformation $authenticationInformation */
        $authenticationInformation = $userProvider->loadUserByUsername($apiKey);
        if (!$authenticationInformation) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiKey)
            );
        }
        Tracer::getInstance()->finishSpan('authorization');

        $obUser = $authenticationInformation->getUser();
        $roles = $obUser ? $obUser->getRoles() : [];

        $token = new PreAuthenticatedToken(
            $obUser,
            $apiKey,
            $providerKey,
            array_merge($roles, $token->getRoles())
        );

        $token->setAttribute('authentication_information', $authenticationInformation);

        return $token;
    }
}
