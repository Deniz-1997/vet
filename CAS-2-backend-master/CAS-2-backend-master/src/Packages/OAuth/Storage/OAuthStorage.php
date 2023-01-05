<?php

namespace App\Packages\OAuth\Storage;

use App\Entity\User\User;
use App\Entity\OAuth\Client;
use App\Packages\Security\RoleManager;
use OAuth2\Model\IOAuth2Client;
use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

/**
 * Class OAuthStorage.
 */
class OAuthStorage extends \FOS\OAuthServerBundle\Storage\OAuthStorage
{
    /**
     * @var UserCheckerInterface
     */
    protected UserCheckerInterface $userChecker;

    /**
     * @var RoleManager
     */
    protected RoleManager $roleManager;

    /**
     * @required
     *
     * @param RoleManager $roleManager
     */
    public function setRoleManager(RoleManager $roleManager)
    {
        $this->roleManager = $roleManager;
    }

    /**
     * @param Client|IOAuth2Client $client
     * @param string               $username
     * @param string               $password
     *
     * @return array|bool
     *
     * @throws OAuth2ServerException
     */
    public function checkUserCredentials(IOAuth2Client $client, $username, $password)
    {
        /** @var false|array $result */
        $result = parent::checkUserCredentials($client, $username, $password);

        if ($result) {
            /** @var User $user */
            $user = $result['data'];

            if ($user->isStatus() === false) {
                throw new OAuth2ServerException(Response::HTTP_BAD_REQUEST, 'inactive_user', "Your access is denied. Contact administrator");
            }
            try {
                $this->userChecker->checkPreAuth($user);
            } catch (DisabledException $exception) {
                throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, 'disabled_user', $exception->getMessage());
            } catch (LockedException $exception) {
                throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, 'locked_user', $exception->getMessage());
            } catch (AccountExpiredException $exception) {
                throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, 'user_expired', $exception->getMessage());
            } catch (AccountStatusException $exception) {
                throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, 'account_status', $exception->getMessage());
            }
        }

        return $result;
    }

    /**
     * @param UserCheckerInterface $userChecker
     */
    public function setUserChecker(UserCheckerInterface $userChecker)
    {
        $this->userChecker = $userChecker;
    }
}
