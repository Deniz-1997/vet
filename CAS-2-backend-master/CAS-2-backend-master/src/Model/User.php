<?php

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class User implements UserInterface
{
    /**
     * @var int|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?int $id;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?string $email;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?string $username;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?string $name;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?string $surname;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?string $patronymic;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "permission.admin",
     * })
     */
    protected ?string $password;

    /**
     * @var string|null
     *
     * @Groups({
     *     "authentication",
     *     "permission.admin",
     * })
     */
    protected ?string $salt;

    /**
     * @var string[]
     *
     * @Groups({
     *     "authentication",
     *     "permission.admin",
     *     "encrypt_token.v1",
     * })
     */
    protected array $roles = [];

    /**
     * @var array|null
     *
     * @Groups({
     *     "authentication",
     *     "encrypt_token.v1",
     * })
     */
    protected ?array $additionalRestrictions = [];

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username)
    {
        $this->username = $username;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt)
    {
        $this->salt = $salt;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param string $role
     */
    public function addRole(string $role)
    {
        $this->roles[] = $role;
    }

    /**
     * @return array
     */
    public function getAdditionalRestrictions(): ?array
    {
        return $this->additionalRestrictions;
    }

    /**
     * @param array $additionalRestrictions
     */
    public function setAdditionalRestrictions(array $additionalRestrictions)
    {
        $this->additionalRestrictions = $additionalRestrictions ?: [];
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @param string|null $patronymic
     */
    public function setPatronymic(?string $patronymic)
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return string
     */
    public function toString(): ?string
    {
        return $this->username;
    }
}
