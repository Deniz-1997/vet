<?php

namespace App\Entity\Leaving;

use App\Entity\Appointment\Appointment;
use App\Entity\Appointment\AppointmentFormTemplate;
use App\Entity\Appointment\AppointmentTemperature;
use App\Entity\Appointment\AppointmentWeight;
use App\Entity\Cash\CashReceipt;
use App\Entity\Owner;
use App\Entity\Pet\Pet;

use App\Entity\Reference\Leaving\LeavingProductItem;
use App\Entity\Reference\Leaving\LeavingStatus;
use App\Entity\Reference\Leaving\ReasonForLeaving;
use App\Entity\Reference\Profession;
use App\Entity\Reference\Unit;
use App\Entity\User\User;

use App\Enum\DocumentOperationTypeEnum;
use App\Enum\DocumentStateEnum;
use App\Enum\LeavingTypeEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;

use Symfony\Component\Validator\Constraints as Assert;

use App\Packages\DBAL\Types\PaymentStateEnum;
use App\Packages\DBAL\Types\PaymentTypeEnum;
use App\Exception\ApiException;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Leaving\LeavingRepository")
 * @ORM\Table(name="leavings", schema="leaving", indexes={@ORM\Index(name="search_deleted", columns={"deleted"})})
 */
class Leaving implements DocumentInterface
{
    const TYPE_PRIMARY = 'PRIMARY';
    const TYPE_SECONDARY = 'SECONDARY';
    const TYPE_EVSD = 'EVSD';

    use OrmIdTrait, OrmDeletedTrait, OrmExternalIdTrait;

    /**
     * @var \DateTime Дата
     * @Groups({"default"})
     * @ORM\Column(type="datetime")
     * @SWG\Property(description="Дата")
     */
    private $date;

    /**
     * @var PaymentStateEnum Статус оплаты, доступные значения:
     * @Assert\NotNull()
     * PAID - Оплачено,
     * NOT_PAID - Не оплачено,
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\PaymentStateEnum::class), example="NOT_PAID")
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentStateEnum", nullable=false, options={"default": PaymentStateEnum::NOT_PAID})
     * @Assert\NotBlank(message="leaving.paymentState.not_blank")
     */
    private $paymentState;

    /**
     * @var LeavingTypeEnum| null
     * @Groups({"default"})
     * @ORM\Column(type="App\Enum\LeavingTypeEnum", nullable=true)
     * @Assert\NotBlank(message="leaving.type.not_blank")
     */
    private $type;

    /**
     * @var Pet|null Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", fetch="EAGER")
     * @SWG\Property(description="Животное")
     */
    private $pet;

    /**
     * @var Owner
     *
     * @Groups({"default"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner")
     */
    private $owner;

    /**
     * @var User| null
     *
     * @Groups({"default"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(description="Специалист")
     */
    private $user;


    /**
     * @var LeavingStatus
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Leaving\LeavingStatus")
     */
    private $leavingStatus;

    /**
     * @var ReasonForLeaving Причина выезда
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Leaving\ReasonForLeaving")
     */
    private $reasonForLeaving;

    /**
     * @var string Жалоба
     * @Groups({
     *     "permission.doctor",
     *     "permission.appointmentAdmin",
     *     "permission.list.receptionist",
     *     "permission.detail.receptionist",
     *     "permission.post.receptionist",
     * })
     * @ORM\Column(type="text")
     * @SWG\Property(description="Жалоба")
     */
    private $name;

    /**
     * @var string| null Диагноз
     * @Groups({"permission.doctor", "permission.appointmentAdmin"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="Диагноз")
     */
    private $diagnosis;

    /**
     * @var string| null Назначение комментарий
     * @Groups({"permission.doctor", "permission.appointmentAdmin"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="Назначение комментарий")
     */
    private $prescription;

    /**
     * @var string| null Обследование
     * @Groups({"permission.doctor", "permission.appointmentAdmin"})
     * @SWG\Property(description="Обследование")
     * @ORM\Column(type="text", nullable=true)
     */
    private $survey;

    /**
     * @var Profession| null Специальность
     * @Groups({"permission.doctor", "permission.appointmentAdmin", "api.appointment"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Profession")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(description="Специальность")
     * @Assert\NotBlank(message="appointment.profession.not_blank")
     */
    private $profession;

    /**
     * @var Leaving Предыдущий выезд
     * @Groups({"permission.doctor", "permission.leavingAdmin", "api.leaving"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Leaving\Leaving")
     */
    private  $previous;

    /**
     * @var Unit Клиника
     * @Groups({"default"})
     * @SWG\Property(description="Клиника")
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Unit")
     */
    private $unit;

    /**
     * @var CashReceipt Чек ККМ
     * @Groups({"permission.doctor", "permission.leavingAdmin", "api.leaving"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Cash\CashReceipt")
     * @SWG\Property(description="Чек ККМ")
     */
    private $cashReceipt;

    /**
     * @var LeavingProductItem[]
     * @Groups({"permission.doctor", "permission.appointmentAdmin", "api.appointment"})
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Reference\Leaving\LeavingProductItem",
     *     mappedBy="leaving",
     *     cascade={"persist"},
     *     orphanRemoval=true
     *    )
     */
    private $productItems;

    /**
     * @var bool Признак наличия наценки на услуги в ночное время
     * @Groups({"permission.doctor", "permission.leavingAdmin", "api.leaving"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean", description="Признак наличия наценки на услуги в ночное время")
     */
    private $isExtraCharge;

    /**
     * @var float|null Наценка на услуги в ночное время
     * @Groups({"permission.doctor", "permission.leavingAdmin", "api.leaving"})
     * @ORM\Column(type="float", nullable=true, options={"default":0}))
     * @SWG\Property(type="number", format="float", description="Наценка на услуги в ночное время")
     */
    private $extraCharge;

    /**
     * @var PaymentTypeEnum|null
     * @Groups({"permission.doctor", "permission.leavingAdmin", "api.leaving"})
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\PaymentTypeEnum::class), example="CASH")
     * @ORM\Column(type="App\Packages\DBAL\Types\PaymentTypeEnum", nullable=true)
     */
    private $paymentType;

    /**
     * @var DocumentStateEnum
     * @Assert\NotNull()
     * @ORM\Column(type=DocumentStateEnum::class, nullable=true, options={"default": DocumentStateEnum::DRAFT})
     * @SWG\Property(ref=@Model(type=DocumentStateEnum::class), description="Статус документа")
     */
    private $state;

    /**
     * @var string|UuidInterface|null
     *
     * @Assert\Uuid(versions={4})
     * @SWG\Property(type="string", example="f5e4ff91-c98d-4fd3-a554-7c35592c5280")
     *
     * @ORM\Column(type="uuid", unique=true, nullable=true)
     */
    private $uuid;

    /**
     * @var array<ApiException>
     * @Groups({"default"})
     * @ORM\Column(type="array", nullable=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ApiException::class)) , description="Массив ошибок при попытке проведения документа")
     */
    private $errors;

    /**
     * @var \DateTime|null Дата регистрации документа
     * @Groups({"default"})
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(description="Дата регистрации документа")
     */
    private $dateEnd;

    /**
     * @var AppointmentFormTemplate[]|null
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment\AppointmentFormTemplate", mappedBy="leaving", cascade={"all"}, orphanRemoval=true)
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=App\Entity\Appointment\AppointmentFormTemplate::class)) , description="Массив шаблонов форм")
     */
    private $appointmentFormTemplate;


    /**
     * @var LeavingTemperature|null
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\LeavingTemperature", mappedBy="leaving", cascade={"persist"})
     */
    private $leavingTemperature;

    /**
     * @var boolean|null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $weightNotMeasured;

    /**
     * @var LeavingWeight|null
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity="App\Entity\Leaving\LeavingWeight", mappedBy="leaving", cascade={"persist"})
     */
    private $leavingWeight;

     /**
     * @var boolean|null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $temperatureNotMeasured;

     /**
     * @var \DateTime|null Дата
     * @Groups({"default"})
     * @SWG\Property(description="Дата последнего изменения")
     */
    private $lastUpdate;

    public function __construct()
    {
        $this->productItems = new ArrayCollection();
        $this->state = clone DocumentStateEnum::getItem(DocumentStateEnum::DRAFT);
        $this->uuid = Uuid::uuid4();
        $this->appointmentFormTemplate = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdate(): ?\DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * @param \DateTime|null $date
     * @return Leaving
     */
    public function setLastUpdate(?\DateTime $date): Leaving
    {
        if (isset($date)) {
            $this->lastUpdate = $date;
        }
        return $this;
    }

    /**
     * @return null|CashReceipt
     */
    public function getCashReceipt(): ?CashReceipt
    {
        return $this->cashReceipt;
    }

    /**
     * @param CashReceipt|null $cashReceipt
     */
    public function setCashReceipt(?CashReceipt $cashReceipt): void
    {
        $this->cashReceipt = $cashReceipt;
    }

    // TODO: Добавить поле Специалист

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Leaving
     */
    public function setDate(\DateTime $date): Leaving
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Pet|null
     */
    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    /**
     * @param \App\Entity\Pet\Pet|null $pet
     * @return Leaving
     */
    public function setPet(?Pet $pet): Leaving
    {
        $this->pet = $pet;
        return $this;
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
     * @return Leaving
     */
    public function setName(string $name): Leaving
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param \App\Entity\Owner|null $owner
     */
    public function setOwner(?Owner $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return LeavingStatus
     */
    public function getLeavingStatus()
    {
        return $this->leavingStatus;
    }

    /**
     * @param LeavingStatus $leavingStatus
     */
    public function setLeavingStatus(LeavingStatus $leavingStatus): void
    {
        $this->leavingStatus = $leavingStatus;
    }
    /**
     * @return ReasonForLeaving
     */
    public function getReasonForLeaving()
    {
        return $this->reasonForLeaving;
    }

    /**
     * @param ReasonForLeaving|null $reasonForLeaving
     */
    public function setReasonForLeaving(?ReasonForLeaving $reasonForLeaving): void
    {
        $this->reasonForLeaving = $reasonForLeaving;
    }

    /**
     * @return string
     */
    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    /**
     * @param string $diagnosis
     * @return Leaving
     */
    public function setDiagnosis(string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrescription(): ?string
    {
        return $this->prescription;
    }

    /**
     * @param string $prescription
     * @return Leaving
     */
    public function setPrescription(string $prescription): self
    {
        $this->prescription = $prescription;

        return $this;
    }

    /**
     * @return LeavingTypeEnum|null
     */
    public function getType(): ?LeavingTypeEnum
    {
        return $this->type;
    }

    /**
     * @param LeavingTypeEnum|null $type
     * @return Leaving
     */
    public function setType(?LeavingTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Profession|null
     */
    public function getProfession(): ?Profession
    {
        return $this->profession;
    }

    /**
     * @param Profession|null $profession
     * @return Leaving
     */
    public function setProfession(?Profession $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * @return Leaving|null
     */
    public function getPrevious(): ?self
    {
        return $this->previous;
    }

    /**
     * @param self|null $previous
     * @return Leaving
     */
    public function setPrevious(?self $previous): self
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurvey(): ?string
    {
        return $this->survey;
    }

    /**
     * @param string|null $survey
     * @return Leaving
     */
    public function setSurvey(?string $survey): self
    {
        $this->survey = $survey;

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
     * @return Leaving
     */
    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return PaymentStateEnum
     */
    public function getPaymentState(): PaymentStateEnum
    {
        return $this->paymentState;
    }

    /**
     * @param PaymentStateEnum $paymentState
     */
    public function setPaymentState(PaymentStateEnum $paymentState): void
    {
        $this->paymentState = $paymentState;
    }

    /**
     * @return Collection|LeavingProductItem[]
     */
    public function getProductItems(): Collection
    {
        return $this->productItems;
    }

    /**
     * @param LeavingProductItem $productItem
     * @return Leaving
     */
    public function addProductItem(?LeavingProductItem $productItem): self
    {
        if (!$this->productItems->contains($productItem)) {
            $this->productItems[] = $productItem;
            $productItem->setLeaving($this);
        }

        return $this;
    }

    /**
     * @param LeavingProductItem $productItem
     * @return Leaving
     */
    public function removeProductItem(LeavingProductItem $productItem): self
    {
        if ($this->productItems->contains($productItem)) {
            $this->productItems->removeElement($productItem);
            // set the owning side to null (unless already changed)
            if ($productItem->getLeaving() === $this) {
                $productItem->setLeaving(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getType() == self::TYPE_SECONDARY && !$this->getPrevious()) {
            $context->buildViolation('Для этого типа должен быть заполнен предыдущий выезд')
                ->atPath('previous')
                ->addViolation();
        }
    }

    /**
     * @return bool
     */
    public function isExtraCharge(): bool
    {
        return $this->isExtraCharge;
    }

    /**
     * @param bool $isExtraCharge
     * @return Leaving
     */
    public function setIsExtraCharge(bool $isExtraCharge): Leaving
    {
        $this->isExtraCharge = $isExtraCharge;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getExtraCharge(): ?float
    {
        return $this->extraCharge;
    }

    /**
     * @param float|null $extraCharge
     * @return Leaving
     */
    public function setExtraCharge(?float $extraCharge): Leaving
    {
        $this->extraCharge = $extraCharge;
        return $this;
    }

    /**
     * @return PaymentTypeEnum|null
     */
    public function getPaymentType(): ?PaymentTypeEnum
    {
        return $this->paymentType;
    }

    /**
     * @param PaymentTypeEnum|null $paymentType
     * @return Leaving
     */
    public function setPaymentType(?PaymentTypeEnum $paymentType): self
    {
        $this->paymentType = $paymentType;
        return $this;
    }


    /**
     * @return string
     * @throws \Exception
     */
    public function getUUID(): string
    {
        if (empty($this->uuid)) {
            $this->uuid = Uuid::uuid4();
        }

        return  $this->uuid->toString();
    }

    /**
     * @return Collection|DocumentProductInterface[]
     */
    public function getDocumentProducts(): Collection
    {
        return $this->getProductItems();
    }

    /**
     * @param DocumentProductInterface $documentProduct
     * @return $this
     */
    public function addDocumentProduct(DocumentProductInterface $documentProduct): DocumentInterface
    {
        return  $this->addProductItem($documentProduct);
    }

    /**
     * @return DocumentOperationTypeEnum|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getOperationType() : ?DocumentOperationTypeEnum
    {
        return DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::LEAVING);
    }

    /**
     * @return DocumentStateEnum
     */
    public function getState(): DocumentStateEnum
    {
        return $this->state;
    }

    /**
     * @param DocumentStateEnum $state
     * @return DocumentInterface
     */
    public function setState(DocumentStateEnum $state): DocumentInterface
    {
        $this->state = clone $state;
        return $this;
    }

    /**
     * @return array<ApiException>|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array<string> $errors
     * @return self
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param string $error
     * @return self
     */
    public function addError(string $error): self
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return DocumentInterface
     */
    public function clearErrors(): DocumentInterface
    {
        $this->errors = [];
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd|null
     * @return self
     */
    public function setDateEnd(?\DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * @return LeavingWeight|null
     */
    public function getLeavingWeight(): ?LeavingWeight
    {
        return $this->leavingWeight;
    }

    /**
     * @param LeavingWeight|null $leavingWeight
     * @return Leaving
     */
    public function setLeavingWeight(?LeavingWeight $leavingWeight): self
    {
        $this->leavingWeight = $leavingWeight;
        return $this;
    }

    /**
     * @return LeavingTemperature|null
     */
    public function getLeavingTemperature(): ?LeavingTemperature
    {
        return $this->leavingTemperature;
    }

    /**
     * @param LeavingTemperature|null $leavingTemperature
     * @return Leaving
     */
    public function setLeavingTemperature(?LeavingTemperature $leavingTemperature): self
    {
        $this->leavingTemperature = $leavingTemperature;
        return $this;
    }



     /**
     * @return bool|null
     */
    public function getTemperatureNotMeasured(): ?bool
    {
        return $this->temperatureNotMeasured;
    }

    /**
     * @param bool|null $temperatureNotMeasured
     * @return Leaving
     */
    public function setTemperatureNotMeasured(?bool $temperatureNotMeasured): self
    {
        $this->temperatureNotMeasured = $temperatureNotMeasured;
        return $this;
    }

      /**
     * @return bool|null
     */
    public function getWeightNotMeasured(): ?bool
    {
        return $this->weightNotMeasured;
    }

    /**
     * @param bool|null $weightNotMeasured
     * @return Leaving
     */
    public function setWeightNotMeasured(?bool $weightNotMeasured): self
    {
        $this->weightNotMeasured = $weightNotMeasured;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function removeCashReceipt() : self
    {
        if ($this->cashReceipt) {
            $this->cashReceipt->setDeleted(true);
            $this->cashReceipt = null;
        }
        return $this;
    }

    /**
     * @return Collection|AppointmentFormTemplate[]|null
     */
    public function getAppointmentFormTemplate(): ?Collection
    {
        return $this->appointmentFormTemplate;
    }

    /**
     * @param AppointmentFormTemplate $appointmentFormTemplate
     * @return Leaving
     */
    public function addAppointmentFormTemplate(AppointmentFormTemplate $appointmentFormTemplate): self
    {
        if (!$this->appointmentFormTemplate->contains($appointmentFormTemplate)) {
            $this->appointmentFormTemplate[] = $appointmentFormTemplate;
            $appointmentFormTemplate->setLeaving($this);
        }

        return $this;
    }


    /**
     * @param AppointmentFormTemplate $appointmentFormTemplate
     * @return Leaving
     */
    public function removeAppointmentFormTemplate(AppointmentFormTemplate $appointmentFormTemplate): self
    {
        if ($this->appointmentFormTemplate->contains($appointmentFormTemplate)) {
            $this->appointmentFormTemplate->removeElement($appointmentFormTemplate);
            // set the owning side to null (unless already changed)
            if ($appointmentFormTemplate->getLeaving() === $this) {
                $appointmentFormTemplate->setLeaving(null);
            }
        }

        return $this;
    }



}
