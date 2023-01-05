<?php

namespace App\Interfaces;


use App\Entity\Reference\Stock;

/**
 * Interface DocumentResearchInterface
 * @package App\Entity\Document
 */
interface DocumentResearchInterface extends DocumentInterface
{
    /**
     * @return Stock|null
     */
    public function getStock() : ?Stock;
}
