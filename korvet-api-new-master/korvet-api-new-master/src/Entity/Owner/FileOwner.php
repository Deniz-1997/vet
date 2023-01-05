<?php

namespace App\Entity\Owner;

use App\Entity\Appointment\Appointment;
use App\Entity\Owner;
use App\Entity\Reference\FileType;
use App\Entity\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(name="reference_owner_files", schema="reference")
 * @ORM\Entity(repositoryClass="App\Repository\Owner\FileOwnerRepository")
 */
class FileOwner
{
    use OrmReferenceTrait, OrmCreatedAtTrait;

    /**
     * @var FileType|null Тип файла
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FileType", fetch="EAGER")
     * @SWG\Property(description="Тип файла")
     */
    private $type;

    /**
     * @var UploadedFile Загруженный файл
     * @ORM\ManyToOne(targetEntity="App\Entity\UploadedFile")
     * @Groups({"default"})
     * @SWG\Property(description="Загруженный файл")
     * @Assert\NotNull(message="file.empty_uploaded_file")
     */
    private $uploadedFile;

    /**
     * @var Owner Владелец
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner")
     * @Groups({"default"})
     * @SWG\Property(description="Загруженный файл")
     * @Assert\NotNull(message="file_owner.owner.not_null")
     */
    private $owner;

    /**
     * @var Appointment|null Обращение
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\Appointment", fetch="EAGER")
     * @ORM\JoinColumn(name="appointment_id", referencedColumnName="id", nullable=true)
     * @SWG\Property(description="Обращение")
     */
    private $appointment;

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
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     */
    public function setOwner(Owner $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return FileType|null
     */
    public function getType(): ?FileType
    {
        return $this->type;
    }

    /**
     * @param FileType|null $type
     */
    public function setType(?FileType $type)
    {
        $this->type = $type;
    }

    /**
     * @return Appointment|null
     */
    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    /**
     * @param Appointment $appointment
     */
    public function setAppointment(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }
}
