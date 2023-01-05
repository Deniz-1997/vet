<?php

namespace App\Entity\Laboratory;

use App\Entity\Contractor;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Reference\Stock;

/**
 * @ORM\Table (schema="laboratory")
 * @ORM\Entity(repositoryClass="App\Repository\Laboratory\LaboratoryRepository")
 */
class Laboratory
{
    use OrmReferenceTrait;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Адрес")
     */
    private ?string $address = null;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Координаты")
     */
    private ?string $coordinates = null;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=50, nullable=true)
     * @SWG\Property(type="string")
     */
    private ?string $phone = null;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(
     *   property="",
     *   type="string",
     *   title="",
     *   format="email",
     *   description="Почта клиники"
     * )
     */
    private ?string $email = null;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(type="string")
     */
    private ?string $website_url = null;

    /**
     * @var Stock|null Склад
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Stock", fetch="EAGER")
     * @ORM\JoinColumn(name="stock_id", referencedColumnName="id", nullable=true)
     * @SWG\Property(type="Склад")
     */
    private ?Stock $stock = null;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean")
     */
    private ?bool $external = null;

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     * @return Laboratory
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    /**
     * @param null|string $coordinates
     * @return Laboratory
     */
    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->website_url;
    }

    public function setWebsiteUrl(?string $website_url): self
    {
        $this->website_url = $website_url;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getExternal(): ?bool
    {
        return $this->external;
    }

    public function setExternal(?bool $external): self
    {
        $this->external = $external;

        return $this;
    }
}
