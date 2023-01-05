<?php

namespace App\Entity\Reference;

use App\Interfaces\OrganizationInterface;
use App\Traits\ORMTraits\OrmSortTrait;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Packages\DBAL\Types\TaxationTypeEnum;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\OrganizationRepository")
 */
class Organization implements OrganizationInterface
{
    use OrmReferenceTrait , OrmSortTrait;

    /**
     * @var string ИНН
     *
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @SWG\Property(
     *   property="inn",
     *   type="string",
     *   title="",
     *   format="",
     *   description="ИНН"
     * )
     *
     * @ORM\Column(type="string", length=10, nullable=false, options={"default": ""})
     * @Assert\NotBlank(message="inn.not_blank")
     */
    private $inn;

    /**
     * @var TaxationTypeEnum
     * @Symfony\Component\Serializer\Annotation\Groups({
     *     "default"
     * })
     * @ORM\Column(type="App\Packages\DBAL\Types\TaxationTypeEnum", nullable=false)
     *
     * @SWG\Property(
     *   property="",
     *   type="object",
     *   title="",
     *   format="",
     *   ref=@Model(type=TaxationTypeEnum::class),
     *   description=""
     * )
     */
    private $taxationType;

    /**
     * @return string
     */
    public function getInn(): string
    {
        return $this->inn;
    }

    /**
     * @param string $inn
     * @return Organization
     */
    public function setInn(string $inn): self
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * @return TaxationTypeEnum
     */
    public function getTaxationType(): TaxationTypeEnum
    {
        return $this->taxationType;
    }

    /**
     * @param TaxationTypeEnum $taxationType
     * @return Organization
     */
    public function setTaxationType(TaxationTypeEnum $taxationType): Organization
    {
        $this->taxationType = $taxationType;
        return $this;
    }
}
