<?php

namespace App\Entity\Reference;

use App\Entity\Contractor;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\UnitRepository")
 */
class Unit
{
    use OrmReferenceTrait, OrmSortTrait;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Адрес")
     */
    private $address;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Координаты")
     */
    private $coordinates;

    /**
     * @var Contractor|null Контрагент
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Contractor", inversedBy="units")
     * @SWG\Property(ref=@Model(type=\App\Entity\Contractor::class), description="Контрагент")
     */
    private $contractor;

    /**
     * @var Product[]|object Номенклатура
     * @Groups({"api.unit"})
     * @ORM\OneToMany(targetEntity="App\Entity\Reference\Product", mappedBy="unit")
     * @SWG\Property(description="Номенклатура")
     */
    private $products;

    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean")
     */
    private $is_around_clock;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(type="string")
     */
    private $full_name;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=50, nullable=true)
     * @SWG\Property(type="string")
     */
    private $phone;

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
    private $email;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(type="string")
     */
    private $website_url;
    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     * @SWG\Property(type="boolean")
     */
    private $without_registry;

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     * @return Unit
     */
    public function setAddress(?string $address): Unit
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
     * @return Unit
     */
    public function setCoordinates(?string $coordinates): Unit
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return Contractor|null
     */
    public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    /**
     * @param Contractor|null $contractor
     * @return Unit
     */
    public function setContractor(?Contractor $contractor): Unit
    {
        $this->contractor = $contractor;
        return $this;
    }

    /**
     * @return Product[]|object
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[]|object $products
     * @return Unit
     */
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    public function getIsAroundClock(): bool
    {
        return $this->is_around_clock;
    }

    public function setIsAroundClock(bool $is_around_clock): self
    {
        $this->is_around_clock = $is_around_clock;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): self
    {
        $this->full_name = $full_name;

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
    public function getWithoutRegistry(): bool
    {
        return $this->without_registry;
    }

    public function setWithoutRegistry(bool $without_registry): self
    {
        $this->without_registry = $without_registry;
        return $this;
    }
}
