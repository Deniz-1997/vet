<?php

namespace App\Interfaces;

use App\Entity\Reference\Product;

/**
 * Interface DocumentProductInterface
 */
interface DocumentProductInterface
{

    /**
     * @return Product|null
     */
    public function getProduct() : ?Product;
}
