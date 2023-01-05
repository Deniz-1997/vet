<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint\UniqueEntity;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Exception\ApiException;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class ProductStock
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductStockRepository")
 * @ORM\Table(
 *     name="product_stock",
 *     schema="product",
 *     uniqueConstraints={@UniqueConstraint(
 *          name="search_idx",
 *          columns={"product_id", "stock_id"}
 *     )
 * })
 * @UniqueEntity(fields={"product", "stock"}, message="productStock.unique_product_stock")
 */
class ProductStock
{
    use OrmIdTrait;

    /**
     * @var Product Оказанные услуги и лекарственные препараты
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var Stock Склад
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="\App\Entity\Reference\Stock")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Stock::class),
     *     description="Склад"
     * )
     */
    private $stock;

    /**
     * @var float|null Количество, остатки
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     * @SWG\Property(description="Количество, остатки")
     */
    private $quantity;

    /**
     * @return \App\Entity\Reference\Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param \App\Entity\Reference\Product $product
     * @return ProductStock
     */
    public function setProduct(Product $product): ProductStock
    {
        $this->product = $product;
        $this->checkExistQuantity();
        return $this;
    }

    /**
     * @return \App\Entity\Reference\Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param \App\Entity\Reference\Stock $stock
     * @return ProductStock
     */
    public function setStock(Stock $stock): ProductStock
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return ProductStock
     * @throws ApiException
     */
    public function setQuantity(?float $quantity): ProductStock
    {
        if ($quantity < 0) {
            throw new ApiException('productStock.wrong_quantity','',null, 400);
        }

        $this->quantity = $quantity;
        $this->checkExistQuantity();
        return $this;
    }

    /**
     * @return bool
     */
    private function checkExistQuantity(): bool
    {
        if (!is_object($this->product)) {
            return false;
        }

        $productQuantity = $this->getQuantity();

        if ($productQuantity > 0) {
            $this->getProduct()->setExistQuantity(true);
        } elseif ($productQuantity == 0) { //type of float
            $count = 0;
            foreach ($this->getProduct()->getProductStock() as $productStock) {
                $count += $productStock->getQuantity();
            }

            if ($count == 0) {//type of float
                $this->getProduct()->setExistQuantity(false);
            } else {
                $this->getProduct()->setExistQuantity(true);
            }
        }

        return true;
    }

    /**
     *  TODO УБРАТЬ ПОСЛЕ ТОГО КАК НАЙДЕТСЯ ОШИБКА ПО КОЛИЧЕСТВАМ НА СКЛАДАХ!!!
     *
     * @return string
     */
    private function _getCallerInfo($quantity)
    {
        $c = date("y:m:d h:i:s") . " Product ID: " . $this->getProduct()->getId() . "; StockID: " . $this->getStock()->getId() . " Quantity: $quantity;";

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        foreach ($trace as $item) {
            if (isset($item['file'])) {
                $c .= basename($item['file']);
            }

            if (isset($item['line'])) {
                $c .= "(" . $item['line'] . ")";
            }

            if (isset($item['function'])) {
                $c .= "->" . $item['function'] . "()";
            }

            if (isset($item['class'])) {
                $c .= " Class: " . $item['class'];
            }

            $c .= "\n";
        }

        return $c . "\n";
    }
}
