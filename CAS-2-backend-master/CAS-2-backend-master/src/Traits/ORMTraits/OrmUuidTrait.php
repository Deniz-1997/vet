<?php

namespace App\Traits\ORMTraits;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;

/**
 * Trait OrmUuidTrait
 */
trait OrmUuidTrait
{
    /**
     * @var string|UuidInterface|null
     *
     * @Assert\Uuid(versions={4})
     * @SWG\Property(type="string", example="f5e4ff91-c98d-4fd3-a554-7c35592c5280")
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class="\Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;
    
    /**
     * @return string|UuidInterface|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id = null)
    {
        $this->id = $id;
    }
    
    /**
     * @return bool
     */
    public function isSetId(): bool
    {
        return !empty($this->id);
    }
}
