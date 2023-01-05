<?php

namespace App\Entity\Reference;

use App\Repository\Reference\CountriesRepository;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table ("reference_countries", schema="reference")
 * @ORM\Entity(repositoryClass=CountriesRepository::class)
 */
class Countries
{
    use OrmNameTrait, OrmIdTrait, OrmDeletedTrait, OrmSortTrait;
}
