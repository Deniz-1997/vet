<?php

namespace App\Entity\Security;

use App\Entity\OAuth\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use OpenApi\Annotations as SWG;

/**
 * Group.
 *
 * @ORM\Table(name="client_groups")
 * @ORM\Entity(repositoryClass="App\Repository\Security\ClientGroupRepository")
 */
class ClientGroup
{
    use OrmIdTrait, OrmNameTrait, OrmDeletedTrait;

    /**
     * @var Client[]
     *
     * @SWG\Property(description="Список клиентов, которые состоят в данной группе")
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\OAuth\Client", inversedBy="groups")
     */
    protected $clients;

    /**
     * @var Role[]
     *
     * @SWG\Property(description="Список ролей, которые привязаны к данной группе клиентам")
     *
     * @Groups({
     *     "api.v1.group.one",
     * })
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\Role", mappedBy="clientGroups", cascade={"persist"})
     */
    protected $roles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add user.
     *
     * @param Client $client
     *
     * @return $this
     */
    public function addClient(Client $client)
    {
        $this->clients[] = $client;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param Client $client
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeClient(Client $client)
    {
        return $this->clients->removeElement($client);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * Add role.
     *
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role)
    {
        $role->addClientGroup($this);

        if (!$this->roles->contains($role)) {
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
            $role->removeClientGroup($this);
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * Get roles.
     *
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
