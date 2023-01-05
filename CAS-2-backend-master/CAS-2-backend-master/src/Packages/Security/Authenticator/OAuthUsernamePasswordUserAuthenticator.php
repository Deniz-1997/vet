<?php

namespace App\Packages\Security\Authenticator;

use App\Packages\Client\AccountClient;
use App\Packages\Client\OAuthClient;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use App\Model\AuthenticationInformation;

/**
 * Class OAuthUsernamePasswordUserProvider
 */
class OAuthUsernamePasswordUserAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var OAuthClient
     */
    private $oauthClient;

    /**
     * @var AccountClient
     */
    private $accountClient;

    /**
     * OAuthUsernamePasswordUserAuthenticator constructor.
     *
     * @param OAuthClient   $oauthClient
     * @param AccountClient $accountClient
     */
    public function __construct(OAuthClient $oauthClient, AccountClient $accountClient)
    {
        $this->oauthClient = $oauthClient;
        $this->accountClient = $accountClient;
    }

    public function createToken(Request $request, $providerKey)
    {
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return UsernamePasswordToken
     * @throws Exception
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey): UsernamePasswordToken
    {
        $username = $token->getUsername();
        $password = $token->getCredentials();

        if ($token->hasAttribute('authentication_information')) {
            /** @var AuthenticationInformation $authenticationInformation */
            $authenticationInformation = $token->getAttribute('authentication_information');

            try {
                $authenticationInformation = $this->accountClient->getCurrentAuthenticationInfo(
                    $authenticationInformation->getAccessToken()
                );
            } catch (Exception $exception) {
                throw new CustomUserMessageAuthenticationException(sprintf('Error fetching user with exist access_token: %s', $exception->getMessage()));
            }
        } else {
            try {
                $tokenInfo = $this->oauthClient->getAccessTokenForUser($username, $password);
            } catch (Exception $exception) {
                throw new CustomUserMessageAuthenticationException(sprintf('Error fetching access token for user: %s', $exception->getMessage()));
            }

            $authenticationInformation = $this->accountClient->getCurrentAuthenticationInfo(
                $tokenInfo->getAccessToken()
            );
        }

        $token = new UsernamePasswordToken(
            $authenticationInformation->getUser(),
            $password,
            $providerKey,
            $authenticationInformation->getUser()->getRoles()
        );

        $token->setAttribute('authentication_information', $authenticationInformation);

        return $token;
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey): bool
    {
        return $token instanceof UsernamePasswordToken;
    }
}
