<?php

namespace App\Entity;

use App\Entity\User\User;
use App\Entity\ReportsData;
use App\Repository\ExplanatoryNoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Entity\UploadedFile;

/**
 * @ORM\Table(schema="reports")
 * @ORM\Entity(repositoryClass=ExplanatoryNoteRepository::class)
 */
class ExplanatoryNote
{

    use OrmIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private ?string $comment;

     /**
     * @var UploadedFile Загруженный файл
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\UploadedFile")
     * @ORM\JoinColumn(nullable=true)
     */
    private $file;

    /**
     * @var User
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var ReportsData
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\ReportsData")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reportData;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return isset($this->comment) ? $this->comment : null;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReportData(): ?ReportsData
    {
        return $this->reportData;
    }

    public function setReportData(?ReportsData $reportData): self
    {
        $this->reportData = $reportData;

        return $this;
    }
}
