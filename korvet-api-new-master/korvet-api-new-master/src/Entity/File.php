<?php

namespace App\Entity;

use App\Entity\Appointment\Appointment;
use App\Entity\Leaving\Leaving;
use App\Entity\Pet\Pet;
use App\Entity\Reference\FileType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Entity\Laboratory\ResearchDocument;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 * @ORM\Table("files")
 */
class File
{
    use OrmReferenceTrait, OrmCreatedAtTrait;

    /**
     * @var Pet Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", fetch="EAGER")
     * @SWG\Property(description="Животное")
     */
    private $pet;

    /**
     * @var FileType|null Тип файла
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\FileType", fetch="EAGER")
     * @SWG\Property(description="Тип файла")
     */
    private $type;

    /**
     * @var Appointment|null Обращение
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Appointment\Appointment", fetch="EAGER")
     * @ORM\JoinColumn(name="appointment_id", referencedColumnName="id", nullable=true)
     * @SWG\Property(description="Обращение")
     */
    private $appointment;
    /**
     * @var Leaving|null Выезд
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Leaving\Leaving", fetch="EAGER")
     * @ORM\JoinColumn(name="leaving_id", referencedColumnName="id", nullable=true)
     * @SWG\Property(description="Выезд")
     */
    private $leaving;

    /**
     * @var UploadedFile Загруженный файл
     * @ORM\ManyToOne(targetEntity="UploadedFile")
     * @Groups({"default"})
     * @SWG\Property(description="Загруженный файл")
     * @Assert\NotNull(message="file.empty_uploaded_file")
     */
    private $uploadedFile;

    /**
     * @var string Ссылка на документ
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="Ссылка на документ")
     */
    private $documentId;

    /**
     * @return Pet|null
     */
    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    /**
     * @param Pet|null $pet
     */
    public function setPet(?Pet $pet)
    {
        $this->pet = $pet;
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

    /**
     * @return Leaving|null
     */
    public function getLeaving(): ?Leaving
    {
        return $this->leaving;
    }

    /**
     * @param Leaving $leaving
     */
    public function setLeaving(Leaving $leaving)
    {
        $this->leaving = $leaving;

    }

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
     * @return string|null
     */
    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    /**
     * @param string|null $documentId
     */
    public function setDocumentId(?string $documentId)
    {
        $this->documentId = $documentId;
    }

}
