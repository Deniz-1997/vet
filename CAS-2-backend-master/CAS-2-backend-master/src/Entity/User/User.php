<?php

namespace App\Entity\User;

use App\Entity\Reference\Station;
use App\Entity\Security\Group;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Validator\Constraint\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 *
 * @UniqueEntity(fields={"username"}, message="user.unique.username")
 * @UniqueEntity(fields={"email"}, message="user.unique.email")
 * @UniqueEntity(fields={"phoneNumber"}, message="user.unique.phone_number")
 */
class User implements UserInterface
{
    use OrmIdTrait, OrmExternalIdTrait, OrmDeletedTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait;

    /**
     * @var Group[]
     *
     * @Groups({
     *     "registration",
     *     "api.v1.user.one",
     *     "api.v1.group.list",
     * })
     *
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=Group::class)), description="Группы, в которые входит пользователь")
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Security\Group", mappedBy="users", fetch="EAGER")
     */
    protected $groups;

    /**
     * @var string
     *
     * @Groups({
     *     "registration",
     *     "default",
     * })
     *
     * @ORM\Column(type="string", length=32)
     */
    private $username;

    /**
     * @var string
     *
     * @Groups({
     *     "registration",
     *     "default",
     * })
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @Groups({
     *     "default",
     *     "registration",
     * })
     *
     * @SWG\Property(description="Имя")
     *
     * Assert\NotBlank()
     *
     * @ORM\Column(type="string", options={"default": ""})
     */
    protected $name;

    /**
     * @var string
     *
     * @Groups({
     *     "default",
     *     "registration",
     * })
     *
     * @SWG\Property(description="Фамилия")
     *
     * Assert\NotBlank()
     *
     * @ORM\Column(type="string", options={"default": ""})
     */
    protected $surname;

    /**
     * @var string|null
     *
     * @Groups({
     *     "default",
     *     "registration",
     * })
     *
     * @SWG\Property(description="Отчество")
     *
     * @ORM\Column(type="string", nullable=true, options={"default": ""})
     */
    protected ?string $patronymic;

    /**
     * @var string
     *
     * @Groups({
     *     "default",
     *     "registration",
     *     "expose"
     * })
     *
     * @SWG\Property(description="Пароль")
     *
     * Assert\NotBlank()
     * Assert\Length(min="4")
     */
    private $plainPassword;

    /**
     * @var array
     * @Groups({"account"})
     */
    private $roles;

    /**
     * @Groups({"except"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $salt;

    /**
     * @var string
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmationChangePasswordCode;

    /**
     * @var string
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmationChangePasswordRecipient;

    /**
     * @var \DateTime
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmationChangePasswordCodeCreatedAt;

    /**
     * @var array|null
     * @Groups({"except"})
     * @ORM\Column(type="json_array", nullable=true, options={"jsonb": true})
     */
    protected $additionalRestrictions = [];

    /**
     * @var array|null
     *
     * @Groups({"default"})
     * @ORM\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @SWG\Property(ref=@Model(type=UserAdditionalFields::class))
     */
    protected $additionalFields = [];

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Номер телефона")
     */
    protected $phoneNumber;

    /**
     * @var bool
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default": 1})
     * @SWG\Property(type="boolean", description="Статус пользователя: активен\не активен")
     */
    protected $status = true;

    /**
     * @var Collection| null
     * @Groups({"default"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Station", mappedBy="users", cascade={"persist"})
     */
    protected ?Collection $stations;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->salt = sha1(time());
        $this->groups = new ArrayCollection();
        $this->professions = new ArrayCollection();
        $this->userSchedules = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->stations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return array_merge($this->roles ?: [], ['ROLE_USER']);
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return ArrayCollection|Group[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     *
     * @return $this
     */
    public function addGroup(Group $group)
    {
        $group->addUser($this);

        if(!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * @param Group $group
     *
     * @return $this
     */
    public function removeGroup(Group $group)
    {
        if($this->getGroups()->contains($group)) {
            $group->removeUser($this);
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmationChangePasswordCode()
    {
        return $this->confirmationChangePasswordCode;
    }

    /**
     * @param string $confirmationChangePasswordCode
     */
    public function setConfirmationChangePasswordCode($confirmationChangePasswordCode)
    {
        $this->confirmationChangePasswordCode = $confirmationChangePasswordCode;
//        $this->confirmationChangePasswordCodeCreatedAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getConfirmationChangePasswordCodeCreatedAt()
    {
        return $this->confirmationChangePasswordCodeCreatedAt;
    }

    /**
     * @param \DateTime $confirmationChangePasswordCodeCreatedAt
     */
    public function setConfirmationChangePasswordCodeCreatedAt(\DateTime $confirmationChangePasswordCodeCreatedAt)
    {
        $this->confirmationChangePasswordCodeCreatedAt = $confirmationChangePasswordCodeCreatedAt;
    }

    /**
     * @return string|null
     */
    public function getConfirmationChangePasswordRecipient()
    {
        return $this->confirmationChangePasswordRecipient;
    }

    /**
     * @param string $confirmationChangePasswordRecipient
     */
    public function setConfirmationChangePasswordRecipient(string $confirmationChangePasswordRecipient)
    {
        $this->confirmationChangePasswordRecipient = $confirmationChangePasswordRecipient;
    }

    /**
     * @return array|null
     */
    public function getAdditionalRestrictions(): ?array
    {
        return $this->additionalRestrictions;
    }

    /**
     * @param array|null $additionalRestrictions
     * @return User
     */
    public function setAdditionalRestrictions(?array $additionalRestrictions): User
    {
        $this->additionalRestrictions = $additionalRestrictions ?: [];
        return $this;
    }

    /**
     * @return array|null
     */
    public function getAdditionalFields(): ?array
    {
        return $this->additionalFields;
    }

    /**
     * @param array|null $additionalFields
     * @return User
     */
    public function setAdditionalFields(?array $additionalFields): User
    {
        $this->additionalFields = $additionalFields ?: [];
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string|null
     */
    public function getPatronymic():?string
    {
        return $this->patronymic;
    }

    /**
     * @param string $patronymic
     */
    public function setPatronymic(?string $patronymic)
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInn()
    {
        if (!$this->additionalFields) {
            return null;
        }

        return $this->additionalFields['inn'] ?? null;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        if (!$this->createdAt) {
            if (!$this->updatedAt) {
                $this->createdAt = new \DateTime();
            } else {
                $this->createdAt = $this->updatedAt;
            }
        }
        return $this->createdAt;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @return Collection|Station[]
     */
    public function getStations(): Collection
    {
        return $this->stations;
    }

    public function setStations(array $stations): self
    {
        $this->stations = $stations;

        return $this;
    }

    public function addStation(Station $station): self
    {
        if (!$this->stations->contains($station)) {
            $this->stations[] = $station;
            $station->addUser($this);
        }

        return $this;
    }

    public function removeStation(Station $station): self
    {
        if ($this->stations->removeElement($station)) {
            $station->removeUser($this);
        }

        return $this;
    }
}
