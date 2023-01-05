<?php

namespace App\Entity\Reference;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use App\Traits\ORMTraits\OrmIdTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;

/**
 * @ORM\Table(schema="form")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\FormFieldPropertyRepository")
 */
class FormFieldProperty
{
    use OrmIdTrait, OrmNameTrait, OrmSortTrait;

    /**
     * @var string
     * @Groups({"default"})
     * @ORM\Column(type="string", unique=true)
     * @SWG\Property(description="Текстовый идентификатор", type="string")
     */
    private $code;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Описание характеристики поля", type="string")
     */
    private $description;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(description="Значение свойства поля по умолчанию", type="string")
     */
    private $defaultValue;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return FormFieldProperty
     */
    public function setCode(string $code): FormFieldProperty
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return FormFieldProperty
     */
    public function setDescription(?string $description): FormFieldProperty
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    /**
     * @param string|null $defaultValue
     * @return FormFieldProperty
     */
    public function setDefaultValue(?string $defaultValue): FormFieldProperty
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }
}
