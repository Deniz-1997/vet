<?php

namespace App\Entity\ApiData;

use App\Repository\ApiData\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Packages\DBAL\Types\TokenTypeEnum;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    use OrmIdTrait, OrmNameTrait;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $accessToken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refreshToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scope;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredIn;

    /**
     * @ORM\Column(type="App\Packages\DBAL\Types\TokenTypeEnum")
     */
    private $tokenType;

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    public function getExpiredIn(): ?string
    {
        return $this->expiredIn;
    }

    public function setExpiredIn($expiredIn): self
    {
        $this->expiredIn = $expiredIn;

        return $this;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function setTokenType(TokenTypeEnum $tokenType): self
    {
        $this->tokenType = $tokenType;

        return $this;
    }
}
