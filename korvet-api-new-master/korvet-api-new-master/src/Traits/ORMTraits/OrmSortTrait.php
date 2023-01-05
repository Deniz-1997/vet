<?php

namespace App\Traits\ORMTraits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait OrmSortTrait
{
    /**
     * @var integer|null Поле для сортировки по-умолчанию (рейтинг)
     *
     * @Groups({
     *     "default",
     * })
     *
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     * @SWG\Property(description="Поле для сортировки по-умолчанию (рейтинг)", type="integer")
     */
    private  $sort;

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int|null $sort
     * @return OrmSortTrait
     */
    public function setSort($sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
