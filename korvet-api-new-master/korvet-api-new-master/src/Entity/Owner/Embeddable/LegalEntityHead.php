<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 29/11/2018
 * Time: 20:12
 */

namespace App\Entity\Owner\Embeddable;


use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class LegalEntityHead extends Person
{
    /**
     * @var string|null Должность
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default"=""})
     * @SWG\Property(type="string", description="Должность")
     */
    protected $position;

    /**
     * @var string|null Зона ответственности
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default"=""})
     * @SWG\Property(type="string", description="Зона ответственности")
     */
    protected $responsibilities;

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position)
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getResponsibilities(): ?string
    {
        return $this->responsibilities;
    }

    /**
     * @param string|null $responsibilities
     */
    public function setResponsibilities(?string $responsibilities)
    {
        $this->responsibilities = $responsibilities;
    }
}
