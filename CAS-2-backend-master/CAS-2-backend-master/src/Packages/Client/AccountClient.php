<?php


namespace App\Packages\Client;

use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use App\Model\AuthenticationInformation;
use App\Model\User;

/**
 * OAuthClient.
 */
class AccountClient
{
    /** @var string */
    private string $url;

    /** @var string */
    private string $version;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /**
     * AccountClient constructor.
     *
     * @param SerializerInterface $serializer
     * @param string $url
     * @param string $version
     */
    public function __construct(SerializerInterface $serializer, string $url = "", $version = 'v1')
    {
        $this->serializer = $serializer;
        $this->url = $url;
        $this->version = $version;
    }

    /**
     * @param string $accessToken
     *
     * @return AuthenticationInformation
     *
     * @throws Exception
     */
    public function getCurrentAuthenticationInfo(string $accessToken): AuthenticationInformation
    {
        if (mb_strtolower(mb_substr($accessToken, 0, 6)) != 'bearer') {
            $accessToken = 'Bearer '.$accessToken;
        }

        try {
            $json = file_get_contents(rtrim($this->url, '/api/').'/api/account/user/', false, stream_context_create([
                'http' => ['header' => 'Authorization: '.$accessToken]
            ]));
        } catch (Exception $exception){
            throw new Exception('Failed to open stream: HTTP request failed!');
        }

        $json = json_encode(json_decode($json, true)['response']);

        /** @var AuthenticationInformation $authenticationInformation */
        $authenticationInformation = $this->serializer->deserialize($json, AuthenticationInformation::class, 'json', ['groups' => ['authentication']]);
        $authenticationInformation->setAccessToken($accessToken);

        return $authenticationInformation;
    }
}

