<?php

namespace App\Packages\Response;

/**
 * OAuthResponse.
 */
class OAuthResponse
{
    /** @var string */
    private $accessToken;

    /** @var string */
    private $tokenType;

    /** @var int */
    private $expiresIn;

    /** @var string */
    private $scope;

    /** @var string */
    private $refreshToken;

    /** @var int */
    private $expiredAt;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return $this
     */
    public function setAccessToken(string $accessToken): OAuthResponse
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     *
     * @return $this
     */
    public function setTokenType(string $tokenType): OAuthResponse
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @param int $expiresIn
     *
     * @return $this
     */
    public function setExpiresIn(int $expiresIn): OAuthResponse
    {
        $this->expiredAt = $expiresIn + time();
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     *
     * @return $this
     */
    public function setScope(string $scope): OAuthResponse
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return $this
     */
    public function setRefreshToken(string $refreshToken): OAuthResponse
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpiredAt(): int
    {
        return $this->expiredAt;
    }
}
