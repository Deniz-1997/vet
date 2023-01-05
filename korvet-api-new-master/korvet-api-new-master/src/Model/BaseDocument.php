<?php

namespace App\Model;

use App\Enum\DocumentOperationTypeEnum;
use App\Enum\DocumentStateEnum;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentProductInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class BaseDocument
 */
abstract class BaseDocument implements DocumentInterface
{
    /**
     * @var DocumentStateEnum
     * @Assert\NotNull()
     * @Groups({"default"})
     * @ORM\Column(type=DocumentStateEnum::class, nullable=false, options={"default": DocumentStateEnum::DRAFT})
     * @SWG\Property(ref=@Model(type=DocumentStateEnum::class), description="Статус документа")
     */
    protected $state;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\DocumentProduct", cascade={"all"},  orphanRemoval=true)
     * @ORM\JoinTable(
     *      schema="product",
     *      joinColumns={@ORM\JoinColumn(referencedColumnName="number", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(
     *          name="document_product_id",
     *          referencedColumnName="id",
     *          onDelete="CASCADE"
     *      )
     * })
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Document\DocumentProduct::class),
     *     description="Документ продукта"
     * )
     * @Groups({"default"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid()
     */
    protected $documentProducts;

    /**
     * @var DocumentOperationTypeEnum|null
     */
    protected ?DocumentOperationTypeEnum $operationType;

    /**
     * BaseDocument constructor.
     * @throws DBALException
     */
    public function __construct()
    {
        $this->documentProducts = new ArrayCollection();
        $this->state = clone DocumentStateEnum::getItem(DocumentStateEnum::DRAFT);
    }

    /**
     * @return DocumentStateEnum
     */
    public function getState(): DocumentStateEnum
    {
        return $this->state;
    }

    /**
     * @param DocumentStateEnum $state
     * @return $this
     */
    public function setState(DocumentStateEnum $state): DocumentInterface
    {
        $this->state = clone $state;
        return $this;
    }

    /**
     * @return Collection|DocumentProductInterface[]
     */
    public function getDocumentProducts(): Collection
    {
        return $this->documentProducts;
    }

    /**
     * @param mixed $documentProducts
     * @return $this
     */
    public function setDocumentProducts($documentProducts): BaseDocument
    {
        $this->documentProducts = $documentProducts;
        return $this;
    }

    /**
     * @param DocumentProductInterface $documentProduct
     * @return $this
     */
    public function addDocumentProduct(DocumentProductInterface $documentProduct): DocumentInterface
    {
        if (!$this->documentProducts->contains($documentProduct)) {
            $this->documentProducts[] = $documentProduct;
        }

        return $this;
    }

    /**
     * @param DocumentProductInterface $documentProduct
     * @return $this
     */
    public function removeDocumentProduct(DocumentProductInterface $documentProduct): self
    {
        if ($this->documentProducts->contains($documentProduct)) {
            $this->documentProducts->removeElement($documentProduct);
        }

        return $this;
    }

    /**
     * @return DocumentOperationTypeEnum|null
     */
    public function getOperationType() : ?DocumentOperationTypeEnum
    {
        return $this->operationType;
    }

}
