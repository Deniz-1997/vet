<?php

namespace App\Packages\Security\Authenticator;

use App\Traits\RoleResolver;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use App\Packages\Security\Encryptor;

/**
 * Class EncodedHeaderUserAuthenticator
 */
class EncodedHeaderUserAuthenticator implements SimplePreAuthenticatorInterface
{
    use RoleResolver;

    /** @var Encryptor */
    private Encryptor $encryptor;

    /**
     * EncodedHeaderUserAuthenticator constructor.
     *
     * @param Encryptor $encryptor
     */
    public function __construct(Encryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey): PreAuthenticatedToken
    {
        if (!$encodedUser = $request->headers->get('Authorization')) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'anon.',
            $encodedUser,
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
     *
     * @throws Exception
     *
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey): PreAuthenticatedToken
    {
        $encryptedUser = $token->getCredentials();

        if (!$encryptedUser) {
            throw new UsernameNotFoundException();
        }

        try {
            $authenticationInformation = $this->encryptor->decryptAuthenticationInformation($encryptedUser);
        } catch (Exception $exception) {
            throw new UsernameNotFoundException();
        }

        $token = new PreAuthenticatedToken(
            $authenticationInformation->getUser(),
            $encryptedUser,
            $providerKey,
            array_merge($authenticationInformation->getUser()->getRoles(), $token->getRoles())
        );

        $token->setAttribute('authentication_information', $authenticationInformation);

        return $token;
    }
}
