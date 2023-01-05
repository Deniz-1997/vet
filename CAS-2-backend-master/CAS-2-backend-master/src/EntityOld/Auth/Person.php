<?php

namespace App\EntityOld\Auth;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\EntityOld\Contractors\SupervisoryAuthority;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Auth\PersonRepository")
 * @ORM\Table(name="auth.person")
 */
class Person
{
    use TimestampableEntity;

    /**
     * @var string $id
     *
     * @Groups({"default"})
     * @SWG\Property(type="guid", example="d3fec963-539e-4dfd-8abc-517673aaa7a8")
     *
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", name="name", length=255, nullable=false)
     * @SWG\Property(type="Имя")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private string $name;

    /**
     * @var string $patronymic
     *
     * @ORM\Column(type="string", name="patronymic", length=255, nullable=false)
     * @SWG\Property(type="Отчество")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private string $patronymic;

    /**
     * @var string $surname
     *
     * @ORM\Column(type="string", name="surname", length=255, nullable=false)
     * @SWG\Property(type="Фамилия")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private string $surname;

    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", name="phone", length=255, nullable=false)
     * @SWG\Property(type="Телефон")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private ?string $phone;

    /**
     * @var SupervisoryAuthority $phone
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\EntityOld\Contractors\SupervisoryAuthority")
     * @SWG\Property(description="Станция")
     */
    private ?SupervisoryAuthority $station;

    /**
     * @var bool $isInvalid
     * @ORM\Column(type="boolean", name="is_invalid", nullable=true, options={"default":false})
     */
    private bool $isInvalid = false;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return ?string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return ?SupervisoryAuthority
     */
    public function getStation(): ?SupervisoryAuthority
    {
        return $this->station;
    }

    /**
     * @param SupervisoryAuthority $station
     */
    public function setStation(SupervisoryAuthority $station): void
    {
        $this->station = $station;
    }

    /**
     * @return bool
     */
    public function getIsInvalid(): bool
    {
        return $this->isInvalid;
    }

    /**
     * @param bool $isInvalid
     */
    public function setIsInvalid(bool $isInvalid): void
    {
        $this->isInvalid = $isInvalid;
    }

    /**
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @param string $patronymic
     */
    public function setPatronymic(string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
        ]);
    }

    /**
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list ($this->id) = unserialize($serialized);
    }
}
