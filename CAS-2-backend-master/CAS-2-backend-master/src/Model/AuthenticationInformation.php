<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class AuthenticationInformation
 */
class AuthenticationInformation
{
    /**
     * @var User
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    private User $user;

    /**
     * @var string|null
     *
     * @Groups({
     *     "encrypt_token.v1",
     * })
     */
    private ?string $accessToken;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    private ?string $clientId;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return null|string
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @param null|string $clientId
     * @return AuthenticationInformation
     */
    public function setClientId(?string $clientId): AuthenticationInformation
    {
        $this->clientId = $clientId;
        return $this;
    }
}
