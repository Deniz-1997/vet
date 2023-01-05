<?php

namespace App\Interfaces;


use App\Entity\Reference\Stock;

/**
 * Interface DocumentStockInterface
 * @package App\Entity\Document
 * интерфейс для документа списания
 */
interface DocumentExpenseInterface extends DocumentInterface
{
    /**
     * @return Stock|null
     */
    public function getStock() : ?Stock;
}
