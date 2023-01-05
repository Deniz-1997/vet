<?php

namespace App\Entity\OAuth;

use App\Entity\Security\ClientGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use OpenApi\Annotations as OA;

/**
 * Client.
 *
 * @ORM\Table(name="oauth_clients")
 * @ORM\Entity(repositoryClass="App\Repository\OAuth\ClientRepository")
 */
class Client extends BaseClient
{
    use OrmNameTrait, OrmDeletedTrait;

    /**
     * @var int
     *
     * @Groups({
     *     "default",
     * })
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Groups({
     *     "detail",
     *     "post",
     *     "patch",
     *     "put",
     *     "delete",
     * })
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\ClientGroup", mappedBy="clients")
     */
    protected $groups;

    /**
     * @var array
     *
     * @Groups({
     *     "detail",
     *     "post",
     *     "patch",
     *     "put",
     *     "delete",
     * })
     *
     * @OA\Property(
     *     type="",
     *     description="Массив, который может содержать в себе следующие значения: [client_credentials, token, refresh_token, password]"
     * )
     */
    protected $allowedGrantTypes = [];

    /**
     * @var array
     *
     * @Groups({
     *     "default",
     *     "api.v1.client.list",
     * })
     *
     * @OA\Property(
     *     type="",
     *     description="Массив с URL-ами для работы авторизации по authorization_code"
     * )
     */
    protected $redirectUris = [];

    /**
     * @var string
     *
     * @Groups({
     *     "detail",
     *     "post",
     *     "patch",
     *     "put",
     *     "delete",
     * })
     *
     * @OA\Property(type="string")
     */
    protected string $clientId;

    /**
     * @var string
     *
     * @Groups({
     *     "detail",
     *     "post",
     *     "patch",
     *     "put",
     *     "delete",
     * })
     *
     * @OA\Property(type="string")
     */
    protected string $clientSecret;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->clientSecret = $this->secret;
        $this->clientId = $this->randomId;
        $this->groups = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isSetId(): bool
    {
        return $this->id !== null && $this->id !== 0;
    }

    /**
     * @return ClientGroup[]|ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param ClientGroup $clientGroup
     *
     * @return $this
     */
    public function addGroup(ClientGroup $clientGroup): Client
    {
        if (!$this->groups->contains($clientGroup)) {
            $clientGroup->addClient($this);
            $this->groups->add($clientGroup);
        }

        return $this;
    }

    /**
     * @param ClientGroup $clientGroup
     *
     * @return $this
     */
    public function removeGroup(ClientGroup $clientGroup): Client
    {
        if ($this->groups->contains($clientGroup)) {
            $clientGroup->removeClient($this);
            $this->groups->removeElement($clientGroup);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->randomId;
    }

    /**
     * @param string $clientId
     * @return $this
     */
    public function setClientId(string $clientId): Client
    {
        $this->randomId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $clientSecret
     * @return $this
     */
    public function setClientSecret(string $clientSecret): Client
    {
        $this->secret = $clientSecret;

        return $this;
    }
}
