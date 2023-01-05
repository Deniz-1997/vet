<?php

namespace App\Entity\Security;

use App\Entity\User\User;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmCodeTrait;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;

/**
 * Group.
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="App\Repository\Security\GroupRepository")
 */
class Group
{
    use OrmCodeTrait, OrmDeletedTrait, OrmExternalIdTrait , OrmSortTrait;

    /**
     * @var int Идентификатор
     *
     * @Groups({
     *     "default",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     * })
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @SWG\Property(description="Идентификатор", type="integer")
     */
    private $id;

    /**
     * @var User[]
     *
     * @Groups({
     *     "api.v1.group.one",
     * })
     *
     * @SWG\Property(title="Пользователи, которые принадлежат группе", type="array", @SWG\Items(ref=@Model(type=User::class)))
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User\User", inversedBy="groups")
     */
    protected $users;

    /**
     * @var Role[]
     *
     * @Groups({
     *     "api.v1.group.one",
     * })
     *
     * @SWG\Property(title="Роли, которые принадлежат группе", type="array", @SWG\Items(ref=@Model(type=Role::class)))
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\Role", mappedBy="groups", cascade={"persist"}, fetch="EAGER")
     */
    protected $roles;

    /**
     * @var string Наименование
     *
     * @Groups({
     *     "default",
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     *     "api.v1.user.one",
     * })
     *
     * @ORM\Column(type="string", nullable=false)
     * @SWG\Property(description="Наименование", type="string")
     */
    protected $name;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSetId(): bool
    {
        return $this->id !== null && $this->id !== 0;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * Add user.
     *
     * @param User $user
     *
     * @return Group
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param User $user
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeUser(User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add role.
     *
     * @param Role $role
     *
     * @return Group
     */
    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $role->addGroup($this);
            $this->roles->add($role);
        }

        return $this;
    }

    /**
     * Remove role.
     *
     * @param Role $role
     *
     * @return $this
     */
    public function removeRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            $role->removeGroup($this);
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * Get roles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
