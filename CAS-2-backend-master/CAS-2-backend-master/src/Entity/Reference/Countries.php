<?php

namespace App\Entity\Reference;

use App\Repository\Reference\CountriesRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table ("reference_countries", schema="reference")
 * @ORM\Entity(repositoryClass=CountriesRepository::class)
 */
class Countries
{
    use OrmNameTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;

     /**
      * @Groups ({"default"})
     * @ORM\Column(
     *     type="string",
     *     length=3,
     *     options={"fixed"=true, "comment"="Цифровой ISO-код"}
     * )
     */
    private $iso;

    public function setIso($iso)
    {
        $this->iso = strtoupper($iso);
    }

    public function getIso()
    {
        return $this->iso;
    }
}
