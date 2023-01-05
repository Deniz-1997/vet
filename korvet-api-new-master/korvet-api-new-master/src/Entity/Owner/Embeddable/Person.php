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
class Person
{
    /**
     * @var FullName|null ФИО
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\FullName", columnPrefix="full_name_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\FullName::class), description="ФИО")
     */
    protected $fullName;

    /**
     * @var string|null Телефон
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default"=""})
     * @SWG\Property(type="string", description="Телефон")
     */
    protected $phone;

    /**
     * @var string|null Email
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default"=""})
     * @SWG\Property(type="string", description="Email")
     */
    protected $email;

    /**
     * @return FullName|null
     */
    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName|null $fullName
     * @return Person
     */
    public function setFullName(?FullName $fullName): Person
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     * @return Person
     */
    public function setPhone(?string $phone): Person
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return Person
     */
    public function setEmail(?string $email): Person
    {
        $this->email = $email;
        return $this;
    }

    
}
