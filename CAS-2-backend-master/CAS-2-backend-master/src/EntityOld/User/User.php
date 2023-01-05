<?php
namespace App\EntityOld\User;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\EntityOld\Auth\Person;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\OldRep\UserRepository")
 * @ORM\Table(name="fos_user", schema="auth")
 */
class User
{
    use TimestampableEntity;

     /**
      * @ORM\Column(type="guid")
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="UUID")
      * @var string
      */
    protected string $id;
}
