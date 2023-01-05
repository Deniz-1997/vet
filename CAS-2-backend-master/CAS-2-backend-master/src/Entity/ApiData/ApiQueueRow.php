<?php

namespace App\Entity\ApiData;

use App\Repository\ApiData\ApiQueueRowRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Packages\DBAL\Types\ApiQueueStatusEnum;
use App\Entity\Reference\BusinesEntity;
use App\Entity\User\User;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Reference\Station;

/**
 * @ORM\Entity(repositoryClass=ApiQueueRowRepository::class)
 * @ORM\Table(schema="import")
 */
class ApiQueueRow
{
    use OrmIdTrait, OrmCreatedAtTrait, OrmExternalIdTrait, OrmUpdatedAtTrait;

    /**
     * @var ApiQueue
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\ApiData\ApiQueue", inversedBy="rows")
     */
    private $apiQueue;

    /**
     * @var ApiQueueStatusEnum
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\ApiQueueStatusEnum")
     */
    private ApiQueueStatusEnum $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hash;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="json")
     */
    private array $data = [];
    /**
     * @Groups({"default"})
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $error = null;

    public function getStatus(): ApiQueueStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ApiQueueStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return  self
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getError(): ?array
    {
        return $this->error;
    }

    public function setError($error): self
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return  ApiQueue
     */
    public function getApiQueue()
    {
        return $this->apiQueue;
    }

    /**
     * @param  ApiQueue  $apiQueue
     *
     * @return  self
     */
    public function setApiQueue(ApiQueue $apiQueue)
    {
        $this->apiQueue = $apiQueue;

        return $this;
    }
}
