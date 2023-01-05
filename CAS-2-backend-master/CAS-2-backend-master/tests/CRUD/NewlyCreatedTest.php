<?php

namespace App\Service\Test\CRUD;

abstract class NewlyCreatedTest extends AbstractCrudTest
{
    /**
     * @var integer|null
     */
    private $createdId;

    /**
     * @return integer|null
     */
    public function getCreatedId(): ?int
    {
        return $this->createdId;
    }

    /**
     * @param int $createdId
     */
    public function setCreatedId(int $createdId): void
    {
        $this->createdId = $createdId;
    }
}
