<?php

namespace App\Packages\OAuth;

use App\Entity\OAuth\Client;
use OAuth2\Model\IOAuth2Client;

/**
 * Class OAuth2.
 */
class OAuth2 extends \OAuth2\OAuth2
{
    /**
     * @param IOAuth2Client $client
     * @param mixed         $data
     * @param null          $scope
     * @param null          $access_token_lifetime
     * @param bool          $issue_refresh_token
     * @param null          $refresh_token_lifetime
     *
     * @return array
     */
    public function createAccessToken(
        IOAuth2Client $client,
        $data,
        $scope = null,
        $access_token_lifetime = null,
        $issue_refresh_token = true,
        $refresh_token_lifetime = null
    ): array
    {
        if ($client instanceof Client) {
            $allowedScopes = [];

            foreach ($client->getGroups() as $clientGroup) {
                foreach ($clientGroup->getRoles() as $role) {
                    $allowedScopes[] = $role;
                }
            }

            $scope = implode(' ', $allowedScopes);
        }

        return parent::createAccessToken(
            $client,
            $data,
            $scope,
            $access_token_lifetime,
            $issue_refresh_token,
            $refresh_token_lifetime
        );
    }

    public function createDefaultAccessToken(IOAuth2Client $client, $data): array
    {
        return parent::createAccessToken($client, $data);
    }
}
