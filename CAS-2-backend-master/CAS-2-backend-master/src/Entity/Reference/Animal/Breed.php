<?php

namespace App\Entity\Reference\Animal;

use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Animal\BreedRepository")
 * @ORM\Table ("breed", schema="animal")
 */
class Breed
{
    use OrmIdTrait, OrmExternalIdTrait, OrmCreatedAtTrait, OrmUpdatedAtTrait, OrmDeletedTrait, OrmNameTrait;

     /**
      * @Groups ({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Animal\Kind")
     * @ORM\JoinColumn(nullable=false)
     * @var Kind
     */
    private $kind;

    /**
     * Get the value of kind
     *
     * @return  Kind
     */ 
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set the value of kind
     *
     * @param  Kind  $kind
     *
     * @return  self
     */ 
    public function setKind(Kind $kind)
    {
        $this->kind = $kind;

        return $this;
    }
}
