<?php

namespace App\Entity;

use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(schema="reports")
 * @ORM\Entity(repositoryClass="App\Repository\ReportsRepository")
 */
class Reports
{
    use OrmNameTrait, OrmIdTrait;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="guid",nullable=false, columnDefinition="uuid not null default uuid_generate_v4()")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private ?string $uuid;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string $uuidTmp
     */
    private ?string $uuidTmp;

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }
    public function getUuidTmp(): ?string
    {
        return $this->uuidTmp;
    }

    public function setUuidTmp(string $uuidTmp): self
    {
        $this->uuidTmp = $uuidTmp;
        return $this;
    }

}
