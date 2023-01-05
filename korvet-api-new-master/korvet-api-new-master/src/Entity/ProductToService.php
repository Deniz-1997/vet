<?php

namespace App\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Validator\Constraint\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Reference\Product;
use App\Packages\DBAL\Types\PaymentObjectEnum;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductToServiceRepository")
 * @ORM\Table("product_service", schema="product")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"paymentService", "paymentProduct"}, message="product_to_service.unique_record")
 */
class ProductToService
{
    use OrmIdTrait;

    /**
     * @var Product Оказанные услуги
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false, name="service_id", referencedColumnName="id")
     * @SWG\Property(description="Оказанные услуги")
     * @Assert\NotBlank(message="product_to_service.payment_service.not_blank")
     */
    private $paymentService;

    /**
     * @var Product Использованные продукты
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false, name="product_id", referencedColumnName="id")
     * @SWG\Property(description="Использованные продукты")
     * @Assert\NotBlank(message="product_to_service.payment_product.not_blank")
     */
    private $paymentProduct;

    /**
     * @return Product
     */
    public function getPaymentService(): Product
    {
        return $this->paymentService;
    }

    /**
     * @param Product $paymentService
     * @return ProductToService
     */
    public function setPaymentService(Product $paymentService): ProductToService
    {
        $this->paymentService = $paymentService;
        return $this;
    }

    /**
     * @return Product
     */
    public function getPaymentProduct(): Product
    {
        return $this->paymentProduct;
    }

    /**
     * @param Product $paymentProduct
     * @return ProductToService
     */
    public function setPaymentProduct(Product $paymentProduct): ProductToService
    {
        $this->paymentProduct = $paymentProduct;
        return $this;
    }

    /**
     * @param LifecycleEventArgs $event
     *
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     * @throws \Doctrine\ORM\ORMException
     */
    public function preSave(LifecycleEventArgs $event)
    {
        if ($this->getPaymentService()->getPaymentObject() != PaymentObjectEnum::SERVICE) {
            throw new \RuntimeException('payment_product.must_be_service');
        }

        if ($this->getPaymentProduct()->getPaymentObject() == PaymentObjectEnum::SERVICE) {
            throw new \RuntimeException('payment_product.must_be_not_service');
        }
    }

}
