<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;

/**
 * Class User
 * @ORM\Embeddable()
 */
class User
{
    /**
     * @var integer|null
     * @Groups({
     *     "default",
     *     "permission.admin",
     * })
     * @SWG\Property(type="integer", example="1")
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $userId;

    /**
     * @var string $data
     * @Groups({
     *     "default",
     *     "permission.admin",
     * })
     * @SWG\Property(type="string", example="", description="Кто внес изменения, имя пользователя")
     * @ORM\Column(length=255, nullable=true)
     */
    protected $username;

    /**
     * @var string|null
     *
     * @Groups({
     *     "default",
     *     "permission.admin",
     * })
     * @SWG\Property(type="string", example="", description="Имя пользователя")
     * @ORM\Column(length=255, nullable=true)
     */
    protected $userFirstname;

    /**
     * @var string|null
     *
     * @Groups({
     *     "default",
     *     "permission.admin",
     * })
     *
     * @SWG\Property(type="string", example="", description="Фамилия пользователя")
     * @ORM\Column(length=255, nullable=true)
     */
    protected $userSurname;

    /**
     * @var string|null
     *
     * @Groups({
     *     "default",
     *     "permission.admin",
     * })
     * @SWG\Property(type="string", example="", description="Отчество пользователя")
     * @ORM\Column(length=255, nullable=true)
     */
    protected $userPatronymic;

    /**
     * @var string|null
     * @Groups({
     *     "default",
     *     "permission.admin",
     * })
     * @SWG\Property(type="string", example="55_3u3bpqxw80oogc8cggos800gscg4s4w8wo0g80oogc8c8")
     * @ORM\Column(type="string", nullable=true)
     */
    protected $clientId;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getUserFirstname(): ?string
    {
        return $this->userFirstname;
    }

    /**
     * @param string|null $userFirstname
     */
    public function setUserFirstname(?string $userFirstname): void
    {
        $this->userFirstname = $userFirstname;
    }

    /**
     * @return string|null
     */
    public function getUserSurname(): ?string
    {
        return $this->userSurname;
    }

    /**
     * @param string $userSurname
     */
    public function setUserSurname(?string $userSurname): void
    {
        $this->userSurname = $userSurname;
    }

    /**
     * @return string|null
     */
    public function getUserPatronymic(): ?string
    {
        return $this->userPatronymic;
    }

    /**
     * @param string $userPatronymic
     */
    public function setUserPatronymic(?string $userPatronymic): void
    {
        $this->userPatronymic = $userPatronymic;
    }

    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     */
    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }
}
