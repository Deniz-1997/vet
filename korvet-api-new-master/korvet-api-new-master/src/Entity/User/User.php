<?php

namespace App\Entity\User;

use App\Packages\DTO\UserAdditionalFields;
use App\Entity\Reference\Profession;
use App\Entity\Reference\Unit;
use App\Entity\Security\Group;
use App\Entity\UserSchedule;
use App\Interfaces\CashierUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
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
class User implements UserInterface, CashierUserInterface, PasswordAuthenticatedUserInterface
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
     * @var string
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
    protected $patronymic;

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
     * @ORM\Column(type="string")
     */
    private $salt;

    /**
     * @var string
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private  $confirmationChangePasswordCode;

    /**
     * @var string| null
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private  $confirmationChangePasswordRecipient;

    /**
     * @var \DateTime| null
     *
     * @Groups({"except"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private  $confirmationChangePasswordCodeCreatedAt;

    /**
     * @var array|null
     * @Groups({"except"})
     * @ORM\Column(type="json", nullable=true, options={"jsonb": true})
     */
    protected  $additionalRestrictions;

    /**
     * @var array|null
     *
     * @Groups({"default"})
     * @ORM\Column(type="json", nullable=true, options={"jsonb": true})
     * @SWG\Property(ref=@Model(type=UserAdditionalFields::class))
     */
    protected ?array $additionalFields = [];

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
    protected $status;

    /**
     * @var Collection|Profession[]
     * @Groups({
     *    "registration",
     *    "default"
     * })
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Profession")
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=Profession::class)), description="Специальности пользователя")
     */
    private $professions;

    /**
     * @var Unit Клиника
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Unit")
     * @SWG\Property(description="Клиника")
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSchedule", mappedBy="employee")
     */
    private  $userSchedules;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private  $mode_cashbox_mobile;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private  $cashbox_device_id;

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
    public function getSalt(): ?string
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
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(?string $password): self
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

        if(!$this->groups->contains($group)) {
            $this->groups->add($group);
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
        if($this->groups->contains($group)) {
            $group->removeUser($this);
            $this->groups->removeElement($group);
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
        $this->confirmationChangePasswordCodeCreatedAt = new \DateTime();
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
        return $this->additionalRestrictions === null ? []: $this->additionalRestrictions;
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
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param string $patronymic
     */
    public function setPatronymic(string $patronymic)
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
     * @return Collection|Profession[]
     */
    public function getProfessions(): Collection
    {
        return $this->professions;
    }

    /**
     * @param Profession $profession
     * @return User
     */
    public function addProfession(Profession $profession): self
    {
        if (!$this->professions->contains($profession)) {
            $this->professions[] = $profession;
        }

        return $this;
    }

    /**
     * @param Profession $profession
     * @return User
     */
    public function removeProfession(Profession $profession): self
    {
        if ($this->professions->contains($profession)) {
            $this->professions->removeElement($profession);
        }

        return $this;
    }

    /**
     * @return Unit|null
     */
    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    /**
     * @param Unit|null $unit
     * @return User
     */
    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection|UserSchedule[]
     */
    public function getUserSchedules(): Collection
    {
        return $this->userSchedules;
    }

    /**
     * @param UserSchedule $userSchedule
     * @return User
     */
    public function addUserSchedule(UserSchedule $userSchedule): self
    {
        if (!$this->userSchedules->contains($userSchedule)) {
            $this->userSchedules[] = $userSchedule;
            $userSchedule->setEmployee($this);
        }

        return $this;
    }

    /**
     * @param UserSchedule $userSchedule
     * @return User
     */
    public function removeUserSchedule(UserSchedule $userSchedule): self
    {
        if ($this->userSchedules->contains($userSchedule)) {
            $this->userSchedules->removeElement($userSchedule);
            // set the owning side to null (unless already changed)
            if ($userSchedule->getEmployee() === $this) {
                $userSchedule->setEmployee(null);
            }
        }

        return $this;
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

    public function getModeCashboxMobile(): ?bool
    {
        return $this->mode_cashbox_mobile;
    }

    public function setModeCashboxMobile(?bool $mode_cashbox_mobile): self
    {
        $this->mode_cashbox_mobile = $mode_cashbox_mobile;

        return $this;
    }

    public function getCashboxDeviceId(): ?int
    {
        return $this->cashbox_device_id;
    }

    public function setCashboxDeviceId(?int $cashbox_device_id): self
    {
        $this->cashbox_device_id = $cashbox_device_id;

        return $this;
    }
}
