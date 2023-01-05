<?php

namespace App\Entity\Appointment;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Appointment\AppointmentTypeRepository")
 */
class AppointmentType
{
    use OrmReferenceTrait;
}
