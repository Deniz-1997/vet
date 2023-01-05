<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 29/11/2018
 * Time: 00:03
 */

namespace App\Entity\Owner\Embeddable;


use App\Entity\Reference\Owner\LegalForm;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class LegalEntity
{
    /**
     * @var string|null КПП
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="КПП")
     */
    private $kpp;

    /**
     * @var string|null ОГРН
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="ОГРН")
     */
    private $ogrn;

    /**
     * @var string|null Юридический адрес
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Юридический адрес")
     */
    private $juridicalAddress;

    /**
     * @var bool|null Фактический адрес совпадает с юридическим
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Фактический адрес совпадает с юридическим")
     */
    private $factAddressIsJuridicalAddress;

    /**
     * @var bool|null Производственный объект
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Производственный объект")
     */
    private $productionFacility;

    /**
     * @var LegalEntityHead|null Руководитель юридического лица
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\LegalEntityHead", columnPrefix="head_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Person::class), description="Руководитель юридического лица")
     */
    private $head;

    /**
     * @return null|string
     */
    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    /**
     * @param null|string $kpp
     * @return LegalEntity
     */
    public function setKpp(?string $kpp): LegalEntity
    {
        $this->kpp = $kpp;
        return $this;
    }
    
    /**
     * @return null|string
     */
    public function getOgrn(): ?string
    {
        return $this->ogrn;
    }

    /**
     * @param null|string $ogrn
     * @return LegalEntity
     */
    public function setOgrn(?string $ogrn): LegalEntity
    {
        $this->ogrn = $ogrn;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getJuridicalAddress(): ?string
    {
        return $this->juridicalAddress;
    }

    /**
     * @param null|string $juridicalAddress
     * @return LegalEntity
     */
    public function setJuridicalAddress(?string $juridicalAddress): LegalEntity
    {
        $this->juridicalAddress = $juridicalAddress;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFactAddressIsJuridicalAddress(): ?bool
    {
        return $this->factAddressIsJuridicalAddress;
    }

    /**
     * @param bool|null $factAddressIsJuridicalAddress
     * @return LegalEntity
     */
    public function setFactAddressIsJuridicalAddress(?bool $factAddressIsJuridicalAddress): LegalEntity
    {
        $this->factAddressIsJuridicalAddress = $factAddressIsJuridicalAddress;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getProductionFacility(): ?bool
    {
        return $this->productionFacility;
    }

    /**
     * @param bool|null $productionFacility
     * @return LegalEntity
     */
    public function setProductionFacility(?bool $productionFacility): LegalEntity
    {
        $this->productionFacility = $productionFacility;
        return $this;
    }

    /**
     * @return LegalEntityHead|null
     */
    public function getHead(): ?LegalEntityHead
    {
        return $this->head;
    }

    /**
     * @param LegalEntityHead|null $head
     * @return LegalEntity
     */
    public function setHead(?LegalEntityHead $head): LegalEntity
    {
        $this->head = $head;
        return $this;
    }


}
