<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImportExportFileRepository")
 */
class ImportExportFile
{
    use OrmReferenceTrait, OrmCreatedAtTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UploadedFile")
     * @var UploadedFile Загруженный файл
     * @Groups({"default"})
     * @SWG\Property(description="Загруженный файл")
     * @Assert\NotNull(message="file.empty_uploaded_file")
     */
    private $uploadedFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return UploadedFile|null
     */
    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile|null $uploadedFile
     * @return $this
     */
    public function setUploadedFile(?UploadedFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
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
     * @param int $size
     * @param string $name
     * @return $this
     */
    public function setSourcePath(int $size, string $name) : self
    {
        $uploadedFile = new UploadedFile();
        $uploadedFile->setMimeType('text/csv');
        $uploadedFile->setSize($size);
        $uploadedFile->setName($name);
        $this->uploadedFile = $uploadedFile;

        return  $this;
    }
}
