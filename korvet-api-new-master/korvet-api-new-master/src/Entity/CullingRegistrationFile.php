<?php

namespace App\Entity;

use App\Enum\CullingRegistrationFileTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="culling_registration_files")
 * @ORM\Entity(repositoryClass="App\Repository\CullingRegistrationFileRepository")
 */
class CullingRegistrationFile
{
    use OrmReferenceTrait, OrmCreatedAtTrait;

    /**
     * @var UploadedFile Загруженный файл
     * @ORM\ManyToOne(targetEntity="App\Entity\UploadedFile")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     * @SWG\Property(description="Загруженный файл")
     * @Assert\NotNull(message="file.empty_uploaded_file")
     */
    private $uploadedFile;

    /**
     * @var CullingRegistration Дикиое животное
     * @ORM\ManyToOne(targetEntity="App\Entity\CullingRegistration", inversedBy="cullingRegistrationFiles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     * @SWG\Property(description="Отлов")
     * @Assert\NotNull(message="wild_animal.not_null")
     */
    private $cullingRegistration;

    /**
     * @var CullingRegistrationFileTypeEnum Тип
     * @Groups({"default"})
     * @ORM\Column(type="App\Enum\CullingRegistrationFileTypeEnum", nullable=false)
     * @SWG\Property(type="object", ref=@Model(type=CullingRegistrationFileTypeEnum::class))
     */
    private $photoType;

    /**
     * @return UploadedFile|null
     */
    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile|null $uploadedFile
     * @return CullingRegistrationFile
     */
    public function setUploadedFile(?UploadedFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * @return CullingRegistration|null
     */
    public function getCullingRegistration(): ?CullingRegistration
    {
        return $this->cullingRegistration;
    }

    /**
     * @param CullingRegistration|null $cullingRegistration
     * @return CullingRegistrationFile
     */
    public function setCullingRegistration(?CullingRegistration $cullingRegistration): self
    {
        $this->cullingRegistration = $cullingRegistration;

        return $this;
    }

    /**
     * @return CullingRegistrationFileTypeEnum|null
     */
    public function getPhotoType(): ?CullingRegistrationFileTypeEnum
    {
        return $this->photoType;
    }

    /**
     * @param CullingRegistrationFileTypeEnum|null $photoType
     * @return CullingRegistrationFile
     */
    public function setPhotoType(?CullingRegistrationFileTypeEnum $photoType): CullingRegistrationFile
    {
        $this->photoType = $photoType;

        return $this;
    }
}
