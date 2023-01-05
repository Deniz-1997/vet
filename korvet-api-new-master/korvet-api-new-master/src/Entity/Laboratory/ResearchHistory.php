<?php

namespace App\Entity\Laboratory;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Laboratory\ResearchReason;
use App\Entity\Laboratory\Research;
use App\Entity\Laboratory\ProbeSampling;
use App\Entity\Laboratory\ResearchEquipment;
use App\Packages\DBAL\Types\ResearchStateEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\User\User;
use Ramsey\Uuid\Uuid;
use App\Traits\ORMTraits\Complex\ORMDocumentTrait;
use App\Entity\Reference\Stock;
use App\Enum\DocumentOperationTypeEnum;
use App\Model\BaseDocument;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentResearchInterface;
use App\Entity\File;

/**
 * @ORM\Table (schema="laboratory")
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\ResearchHistoryRepository")
 */
class ResearchHistory
{
    use OrmDeletedTrait, OrmIdTrait, OrmUpdatedAtTrait;

    /**
     * @var string Исследование
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=false)
     * @SWG\Property(description="Исследование")
     */
    private string $researchDocumentId;

    /**
     * @var ResearchStateEnum Статус
     * @Groups({"default"})
     * @Assert\NotNull()
     * @SWG\Property(ref=@Model(type=App\Packages\DBAL\Types\ResearchStateEnum::class))
     * @ORM\Column(type="App\Packages\DBAL\Types\ResearchStateEnum", nullable=false)
     * @Assert\NotBlank(message="laboratory.probe_sampling_state.not_blank")
     */
    private ResearchStateEnum $status;

     /**
     * @var User| null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(description="Пользователь")
     */
    private ?User $user = null;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getResearchDocumentId()
    {
        return $this->researchDocumentId;
    }

    /**
     * @param string $researchDocumentId
     * @return \App\Entity\Laboratory\ResearchHistory
     */
    public function setResearchDocumentId(string $researchDocumentId): self
    {
        $this->researchDocumentId = $researchDocumentId;
        return $this;
    }

    /**
     * @return ResearchStateEnum
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ResearchStateEnum $status
     * @return \App\Entity\Laboratory\ResearchHistory
     */
    public function setStatus(ResearchStateEnum $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return \App\Entity\Laboratory\ResearchHistory
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    
}
