<?php

namespace App\Packages\DTO;

use App\Entity\Security\Group;
use App\Entity\User\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use App\Entity\OAuth\Client;

/**
 * Class UserDTO.
 */
class UserDTO
{
    /**
     * @var Group[]
     *
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=Group::class)))
     *
     * @Groups({"default"})
     */
    private array $groups;

    /**
     * @var User|null
     *
     * @SWG\Property(type="object", ref=@Model(type=User::class))
     *
     * @Groups({"default"})
     */
    private ?User $user;


    /**
     * @var string|null
     *
     * @SWG\Property(type="string")
     *
     * @Groups({"default"})
     */
    private $clientId;


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param Group[] $groups
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;
    }


    /**
     * @return ClientId
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     * @return UserDTO
     */
    public function setClientId(string $clientId = null): self
    {
        $this->clientId = $clientId;
        return $this;
    }
}
