<?php

namespace App\EntityOld\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\CountryRepository")
 * @ORM\Table(
 *      name="dictionary.country",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="alpha2_uniq",columns={"alpha2"}),
 *          @ORM\UniqueConstraint(name="alpha3_uniq",columns={"alpha3"}),
 *      }
 * )
 */
class Country
{
    use TimestampableEntity;

    const RUSSIA_ISO = '643';

    /**
     * @ORM\Column(
     *     type="string",
     *     length=3,
     *     options={"fixed"=true, "comment"="Цифровой ISO-код"}
     * )
     * @ORM\Id
     */
    private $iso;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /** @ORM\Column(type="string") */
    private $fullname;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=2,
     *     options={"fixed"=true, "comment"="Двухбуквенный ISO-код"}
     * )
     */
    private $alpha2;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=3,
     *     options={"fixed"=true, "comment"="Трехбуквенный ISO-код"}
     * )
     */
    private $alpha3;

    /**
     * @ORM\OneToMany(
     *      targetEntity="App\EntityOld\Dictionary\Manufacturer",
     *      mappedBy="country"
     * )
     */
    private $manufacturers;

    /** @ORM\Column(name="is_invalid", type="boolean", options={"default" = false}) */
    private $isInvalid = false;

    public function __construct()
    {
        $this->manufacturers = new ArrayCollection;
    }

    public function __toString()
    {
        return $this->name !== null ? $this->name : 'Country name not set.';
    }

    /**
     * @param $name
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    public function getFullname()
    {
        return $this->fullname;
    }

    public function setAlpha2($alpha2)
    {
        $this->alpha2 = $alpha2;
    }

    public function getAlpha2()
    {
        return $this->alpha2;
    }

    public function setAlpha3($alpha3)
    {
        $this->alpha3 = $alpha3;
    }

    public function getAlpha3()
    {
        return $this->alpha3;
    }

    public function setIso($iso)
    {
        $this->iso = strtoupper($iso);
    }

    public function getIso()
    {
        return $this->iso;
    }

    public function setManufacturers($manufacturers)
    {
        $this->manufacturers = $manufacturers;
    }

    public function getManufacturers()
    {
        return $this->manufacturers;
    }

    public function setIsInvalid($isInvalid)
    {
        $this->isInvalid = $isInvalid;
    }

    public function getIsInvalid()
    {
        return $this->isInvalid;
    }

    public function addManufacturer(Manufacturer $manufacturers)
    {
        $this->manufacturers[] = $manufacturers;

        return $this;
    }

    public function removeManufacturer(Manufacturer $manufacturers)
    {
        $this->manufacturers->removeElement($manufacturers);
    }

    /**
     * @param $a Country
     * @param $b Country
     * @return bool|int
     */
    public static function comparatorForBuildingDropdown($a, $b) {
        if ($a->getIso() === Country::RUSSIA_ISO) {
            return -1;
        }
        if ($b->getIso() === Country::RUSSIA_ISO) {
            return 1;
        } else {
            return strcmp($a->getName(), $b->getName());
        }

    }
}
