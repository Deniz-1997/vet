<?php

namespace App\Entity\Reference;



use App\Entity\User\User;
use App\Packages\DBAL\Types\LegalFormsEnum;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(schema="structure")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\BusinesEntityRepository")
 */
class BusinesEntity
{

    use OrmIdTrait, OrmNameTrait, OrmCreatedAtTrait, OrmDeletedTrait, OrmUpdatedAtTrait, OrmExternalIdTrait;

    /**
     * @var LegalFormsEnum Правовые формы
     * @Assert\Expression(expression="this.getLegalForms() and this.getLegalForms().code", message="legalForms.type.not_empty")
     * @Groups({"default"})
     * @ORM\Column(type=LegalFormsEnum::class)
     */
    private LegalFormsEnum $legalForms;

    /**
     * @var string|null Полный адрес строкой
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $legalAddres;

    /**
     * @var string|null КПП
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $kpp;

    /**
     * @var string|null ОГРН
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $ogrn;

    /**
     * @var string|null ИНН
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $inn;

    /**
     * @var string|null БИК
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $bik;

    /**
     * @var string|null Полноре имя  руководителя
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $headFullName;

    /**
     * @var string|null Должность
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $headOffice;

    /**
     * @var string|null Сайт
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $website;

    /**
     * @var \DateTime Дата регистрации
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     */
    private \DateTime $registrationDate;

    /**
     * @var \DateTime|null Дата ликвидации
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $liquidationDate;

    /**
     * @var string|null Комментарий
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $comment;

    /**
     * @var string|null Банк
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $bank = null;


    /**
     * @var integer|null План на месяц
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $planMonth = null;

    /**
     * @var integer|null План на год
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true)
     */
    private  ?int $planSkipYear = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $checkingAccount = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $corAccount = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $businessSize = null;


    /**
     * @var bool| null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private ?bool $workingWithSocialObj = null;

    /**
     * @var integer|null Последняя проверка
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true)
     */
    private  ?int $lastCheck = null;

    /**
     * @var integer|null Риски
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $riskPoints = null;

     /**
     * @var ArrayCollection
     * @Groups({"default"})
     * @ORM\ManyToMany(targetEntity="App\Entity\User\User")
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
     * @return LegalFormsEnum
     */
    public function getLegalForms(): ?LegalFormsEnum
    {
        return $this->legalForms;
    }

    /**
     * @param LegalFormsEnum $legalForms
     * @return BusinesEntity
     */
    public function setLegalForms(LegalFormsEnum $legalForms): self
    {
        $this->legalForms = $legalForms;
        return $this;
    }

    public function getLegalAddres(): ?string
    {
        return $this->legalAddres;
    }

    public function  setLegalAddres(?string $legalAddres): self
    {
        $this->legalAddres = $legalAddres;
        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function  setInn(?string $inn): self
    {
        $this->inn = $inn;
        return $this;
    }

    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    public function  setKpp(?string $kpp): self
    {
        $this->kpp = $kpp;
        return $this;
    }

    public function getOgrn(): ?string
    {
        return $this->ogrn;
    }

    public function  setOgrn(?string $ogrn): self
    {
        $this->ogrn = $ogrn;
        return $this;
    }

    public function getBik(): ?string
    {
        return $this->bik;
    }

    public function  setBik(?string $bik): self
    {
        $this->bik = $bik;
        return $this;
    }

    public function getHeadFullName(): ?string
    {
        return $this->headFullName;
    }

    public function  setHeadFullName(?string $headFullName): self
    {
        $this->headFullName = $headFullName;
        return $this;
    }

    public function getHeadOffice(): ?string
    {
        return $this->headOffice;
    }

    public function  setHeadOffice(?string $headOffice): self
    {
        $this->headOffice = $headOffice;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function  setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getRegistrationDate(): ?\DateTime
    {
        return $this->registrationDate;
    }

    public function  setRegistrationDate(?\DateTime $registrationDate): self
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    public function getLiquidationDate(): ?\DateTime
    {
        return $this->liquidationDate;
    }

    public function  setLiquidationDate(?\DateTime $liquidationDate): self
    {
        $this->liquidationDate = $liquidationDate;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function  setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function  setBank(?string $bank): self
    {
        $this->bank = $bank;
        return $this;
    }

    public function getPlanMonth(): ?int
    {
        return $this->planMonth;
    }

    public function  setPlanMonth(?int $planMonth): self
    {
        $this->planMonth = $planMonth;
        return $this;
    }

    public function getPlanSkipYear(): ?int
    {
        return $this->planSkipYear;
    }

    public function  setPlanSkipYear(?int $planSkipYear): self
    {
        $this->planSkipYear = $planSkipYear;
        return $this;
    }

    public function getCheckingAccount(): ?string
    {
        return $this->checkingAccount;
    }

    public function  setCheckingAccount(?string $checkingAccount): self
    {
        $this->checkingAccount = $checkingAccount;
        return $this;
    }

    public function getCorAccount(): ?string
    {
        return $this->corAccount;
    }

    public function  setCorAccount(?string $corAccount): self
    {
        $this->corAccount = $corAccount;
        return $this;
    }

    public function getBusinessSize(): ?string
    {
        return $this->businessSize;
    }

    public function  setBusinessSize(?string $businessSize): self
    {
        $this->businessSize = $businessSize;
        return $this;
    }

    public function getWorkingWithSocialObj(): ?bool
    {
        return $this->workingWithSocialObj;
    }

    public function  setWorkingWithSocialObj(?bool $workingWithSocialObj): self
    {
        $this->workingWithSocialObj = $workingWithSocialObj;
        return $this;
    }

    public function getLastCheck(): ?int
    {
        return $this->lastCheck;
    }

    public function  setLastCheck(?int $lastCheck): self
    {
        $this->lastCheck = $lastCheck;
        return $this;
    }

    public function getRiskPoints(): ?int
    {
        return $this->riskPoints;
    }

    public function  setRiskPoints(?int $riskPoints): self
    {
        $this->riskPoints = $riskPoints;
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
