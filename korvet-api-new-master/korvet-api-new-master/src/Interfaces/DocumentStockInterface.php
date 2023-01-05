<?php

namespace App\Interfaces;


use App\Entity\Reference\Stock;

/**
 * Interface DocumentStockInterface
 * @package App\Entity\Document
 * интерфейс для документов которые работают с одним складом
 */
interface DocumentStockInterface extends DocumentInterface
{
    /**
     * @return Stock|null
     */
    public function getStock() : ?Stock;
}
