<?php

namespace App\Filter\Type;

use Doctrine\ORM\QueryBuilder;

/**
 * Class FullNameFilter
 */
class FullNameNestedFilter
{
    public function applyFilter(QueryBuilder $builder, array $fields, array $nameData)
    {
        $nameData = array_map('mb_strtolower', $nameData);

        switch (count($nameData)) {
            case 1:
                $builder->andWhere(
                    $builder->expr()->orX(
                        $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                        $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                        $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                    )
                );
                break;
            case 2:
                $builder->andWhere(
                    $builder->expr()->orX(
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[1].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[1].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                        )
                    )
                );
                break;
            case 3:
                $builder->andWhere(
                    $builder->expr()->orX(
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[2].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[2].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[1].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[2].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[2].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[2].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[1].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[0].'%'))
                        ),
                        $builder->expr()->andX(
                            $builder->expr()->like('LOWER('.$fields[0].')', $builder->expr()->literal('%'.$nameData[2].'%')),
                            $builder->expr()->like('LOWER('.$fields[1].')', $builder->expr()->literal('%'.$nameData[0].'%')),
                            $builder->expr()->like('LOWER('.$fields[2].')', $builder->expr()->literal('%'.$nameData[1].'%'))
                        )
                    )
                );
                break;
        }
    }

    public function applyOwnerFilter(QueryBuilder $builder, string $field, string $name)
    {
        $builder->andWhere(
            $builder->expr()->like('LOWER('.$field.')', $builder->expr()->literal('%'.$name.'%')),
        );
    }
}
