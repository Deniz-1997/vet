<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrintFormsRepository")
 */
class PrintForm
{
    const TYPE_OWNER = 'Owner';
    const TYPE_PET = 'Pet';
    const TYPE_APPOINTMENT = 'Appointment';
    const TYPE_LEAVING = 'Leaving';

    const TYPE_OWNER_AND_PET = 'Owner+Pet';
    const TYPE_OWNER_AND_PET_AND_APPOINTMENT = 'Owner+Appointment+Pet';
    const TYPE_OWNER_AND_APPOINTMENT = 'Owner+Appointment';
    const TYPE_PET_AND_APPOINTMENT = 'Pet+Appointment';
    const TYPE_OWNER_AND_PET_AND_LEAVING = 'Owner+Leaving+Pet';
    const TYPE_OWNER_AND_LEAVING = 'Owner+Leaving';
    const TYPE_PET_AND_LEAVING = 'Pet+Leaving';


    use OrmIdTrait;
    use OrmNameTrait;
    use OrmDeletedTrait;

    /**
     * @var UploadedFile
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="UploadedFile")
     */
    private $file;

    /**
     * @var string
     * @Groups({"default"})
     * @Assert\Choice(choices={PrintForm::TYPE_OWNER,
     *     PrintForm::TYPE_LEAVING,
     *     PrintForm::TYPE_OWNER_AND_PET_AND_LEAVING,
     *     PrintForm::TYPE_OWNER_AND_LEAVING,
     *     PrintForm::TYPE_PET_AND_LEAVING,
     *     PrintForm::TYPE_PET,
     *     PrintForm::TYPE_OWNER_AND_PET_AND_APPOINTMENT,
     *     PrintForm::TYPE_PET_AND_APPOINTMENT,
     *     PrintForm::TYPE_OWNER_AND_APPOINTMENT,
     *     PrintForm::TYPE_OWNER_AND_PET,
     *     PrintForm::TYPE_APPOINTMENT})
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $originFileName;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginFileName(): ?string
    {
        return $this->originFileName;
    }

    /**
     * @param string|null $originFileName
     * @return PrintForm
     */
    public function setOriginFileName(?string $originFileName): PrintForm
    {
        $this->originFileName = $originFileName;
        return $this;
    }
}
