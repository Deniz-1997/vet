<?php

namespace App\Entity\Reference;

use App\Entity\User\User;
use App\Packages\Annotation\IgnoreDeleted;
use App\Packages\Annotation\SerializeNestedIgnore;
use App\Repository\Reference\StationRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmExternalIdTrait;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 * @ORM\Table("reference_station", schema="reference")
 */
class Station
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmExternalIdTrait;

    /**
     * @AppAssert\MaxParent(maxParentLevel=4)
     * @IgnoreDeleted()
     * @Groups({"default"})
     * @var Station |null
     * @ORM\JoinColumn(nullable=true)
     * @SerializeNestedIgnore()
     * @ORM\ManyToOne (targetEntity="App\Entity\Reference\Station")
     */
    private ?Station $parent;

    /**
     * @var ArrayCollection
     * @Groups({"default"})
     * @ORM\ManyToMany(targetEntity="App\Entity\User\User", inversedBy="stations", cascade={"persist"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *          name="user_id",
     *          referencedColumnName="id",
     *      )
     * })
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection;
    }


    /**
     * @return Station|null
     */
    public function getParent(): ?Station
    {
        return $this->parent;
    }

    /**
     * @param Station|null $parent
     * @return $this
     */
    public function setParent(?Station $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
