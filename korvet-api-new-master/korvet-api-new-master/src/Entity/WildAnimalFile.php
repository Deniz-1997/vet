<?php

namespace App\Entity;

use App\Enum\WildAnimalFileTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="wild_animal_files")
 * @ORM\Entity(repositoryClass="App\Repository\WildAnimalFileRepository")
 */
class WildAnimalFile
{
    use OrmReferenceTrait, OrmCreatedAtTrait;

    /**
     * @var UploadedFile Загруженный файл
     * @ORM\ManyToOne(targetEntity="App\Entity\UploadedFile")
     * @Groups({"default"})
     * @SWG\Property(description="Загруженный файл")
     * @Assert\NotNull(message="file.empty_uploaded_file")
     */
    private $uploadedFile;


    /**
     * @var WildAnimal Дикиое животное
     * @ORM\ManyToOne(targetEntity="App\Entity\WildAnimal", inversedBy="wildAnimalFiles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"default"})
     * @SWG\Property(description="Дикиое животное")
     * @Assert\NotNull(message="wild_animal.not_null")
     */
    private $wildAnimal;

    /**
     * @var WildAnimalFileTypeEnum Тип
     * @Groups({"default"})
     * @ORM\Column(type="App\Enum\WildAnimalFileTypeEnum", nullable=true)
     * @SWG\Property(type="object", ref=@Model(type=WildAnimalFileTypeEnum::class))
     */
    private $photoType;

    /**
     * @return UploadedFile
     */
    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile $uploadedFile
     */
    public function setUploadedFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }


    /**
     * @return WildAnimal|null
     */
    public function getWildAnimal(): ?WildAnimal
    {
        return $this->wildAnimal;
    }

    /**
     * @param WildAnimal|null $wildAnimal
     * @return WildAnimalFile
     */
    public function setWildAnimal(?WildAnimal $wildAnimal): self
    {
        $this->wildAnimal = $wildAnimal;

        return $this;
    }

    /**
     * @return WildAnimalFileTypeEnum|null
     */
    public function getPhotoType(): ?WildAnimalFileTypeEnum
    {
        return $this->photoType;
    }

    /**
     * @param WildAnimalFileTypeEnum|null $photoType
     * @return WildAnimalFile
     */
    public function setPhotoType(?WildAnimalFileTypeEnum $photoType): WildAnimalFile
    {
        $this->photoType = $photoType;

        return $this;
    }
}
