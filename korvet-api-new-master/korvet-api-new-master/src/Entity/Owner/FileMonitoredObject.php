<?php

namespace App\Entity\Owner;

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
 * @ORM\Table(name="monitored_objects_files")
 * @ORM\Entity(repositoryClass="App\Repository\Owner\FileMonitoredObjectRepository")
 */
class FileMonitoredObject
{
    use OrmReferenceTrait, OrmCreatedAtTrait;

    /**
     * @var FileType Тип файла
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
     * @var MonitoredObject Подконтрольный объект
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner\MonitoredObject")
     * @Groups({"default"})
     * @SWG\Property(description="Подконтрольный объект")
     * @Assert\NotNull(message="file_monitored_object.monitored_object.not_null")
     */
    private $monitoredObject;

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
     * @return FileType
     */
    public function getType(): FileType
    {
        return $this->type;
    }

    /**
     * @param FileType $type
     */
    public function setType(FileType $type)
    {
        $this->type = $type;
    }

    /**
     * @return MonitoredObject
     */
    public function getMonitoredObject(): MonitoredObject
    {
        return $this->monitoredObject;
    }

    /**
     * @param MonitoredObject $monitoredObject
     * @return $this
     */
    public function setMonitoredObject(MonitoredObject $monitoredObject): self
    {
        $this->monitoredObject = $monitoredObject;

        return $this;
    }
}
