<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\OrmSortTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmExternalIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reference\EventTypeRepository")
 * @ORM\Table("reference_event_types", schema="reference")
 */
class EventType
{
    use OrmSortTrait; use OrmReferenceTrait, OrmExternalIdTrait;
}
