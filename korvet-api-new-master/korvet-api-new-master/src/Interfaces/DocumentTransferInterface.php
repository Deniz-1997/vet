<?php

namespace App\Interfaces;


use App\Entity\Reference\Stock;

/**
 * Interface DocumentTransferInterface
 * @package App\Entity\Document
 * для документов передвижения
 */
interface DocumentTransferInterface extends DocumentInterface
{
    /**
     * @return Stock
     */
    public function getStockTo() : Stock;

    /**
     * @return Stock
     */
    public function getStockFrom() : Stock;
}
