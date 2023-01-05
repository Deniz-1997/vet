<?php

namespace App\Entity\Security;

use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use OpenApi\Annotations as SWG;

/**
 * Role.
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="App\Repository\Security\RoleRepository")
 */
class Role
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @var Role
     *
     * @Groups({
     *     "api.v1.role.one",
     * })
     *
     * @SWG\Property(title="Родительская роль", type="object", ref=@Model(type=Role::class))
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\Role", inversedBy="children", fetch="EAGER")
     */
    protected $parent;

    /**
     * @var Role[]
     *
     * @Groups({
     *     "api.v1.role.one",
     * })
     *
     * @SWG\Property(title="Дочерние роли", type="array", @SWG\Items(ref=@Model(type=Role::class)))
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Security\Role", mappedBy="parent", fetch="EAGER")
     */
    protected $children;

    /**
     * @var Group[]
     *
     * @Groups({
     *     "api.v1.role.one",
     * })
     *
     * @SWG\Property(
     *     description="Группы пользователей, к которым привязана данная роль",
     *     type="array",
     *     @SWG\Items(ref=@Model(type=Group::class))
     * )
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\Group", inversedBy="roles", cascade={"PERSIST"})
     */
    protected $groups;

    /**
     * @var ClientGroup[]
     *
     * @Groups({
     *     "api.v1.role.one",
     * })
     *
     * @SWG\Property(
     *     description="Группы клиентов, к которым привязана данная роль",
     *     type="array",
     *     @SWG\Items(ref=@Model(type=ClientGroup::class))
     * )
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\ClientGroup", inversedBy="roles")
     */
    protected $clientGroups;

    /**
     * @var string Строковый идентификатор элемента
     *
     * @Groups({
     *     "api.v1.group.roles",
     *     "api.v1.group.list",
     *     "api.v1.group.one",
     * })
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @SWG\Property(description="Текстовый идентификатор", type="string")
     */
    private $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor.
     *
     * @param string $code
     */
    public function __construct($code = null)
    {
        if ($code) {
            $this->code = $code;
        }

        $this->groups = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->clientGroups = new ArrayCollection();
    }

    /**
     * Add group.
     *
     * @param Group $group
     *
     * @return Role
     */
    public function addGroup(Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group.
     *
     * @param Group $group
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeGroup(Group $group)
    {
        return $this->groups->removeElement($group);
    }

    /**
     * Get groups.
     *
     * @return Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Role $parent
     *
     * @return $this
     */
    public function setParent(Role $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Role[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Role $children
     *
     * @return $this
     */
    public function addChildren(Role $children)
    {
        if (!$this->children->contains($children)) {
            $children->setParent($this);
            $this->children->add($children);
        }

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function removeChildren(Role $role)
    {
        if ($this->children->contains($role)) {
            $this->children->removeElement($role);
        }

        return $this;
    }

    /**
     * @return ClientGroup[]
     */
    public function getClientGroups()
    {
        return $this->clientGroups;
    }

    /**
     * @param ClientGroup $clientGroups
     * @return $this
     */
    public function addClientGroup($clientGroups)
    {
        if (!$this->clientGroups->contains($clientGroups)) {
            $this->clientGroups->add($clientGroups);
        }

        return $this;
    }

    /**
     * @param ClientGroup $clientGroups
     * @return $this
     */
    public function removeClientGroup($clientGroups)
    {
        if ($this->clientGroups->contains($clientGroups)) {
            $this->clientGroups->removeElement($clientGroups);
        }

        return $this;
    }
}
