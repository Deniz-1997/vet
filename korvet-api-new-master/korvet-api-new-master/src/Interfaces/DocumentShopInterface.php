<?php

namespace App\Interfaces;


use App\Entity\Reference\Stock;

/**
 * Interface DocumentShopInterface
 * @package App\Entity\Document
 */
interface DocumentShopInterface extends DocumentInterface
{
    /**
     * @return Stock|null
     */
    public function getStock() : ?Stock;
}
