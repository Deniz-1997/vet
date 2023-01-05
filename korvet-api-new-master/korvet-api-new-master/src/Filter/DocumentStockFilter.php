<?php

namespace App\Filter;

use App\Entity\Document\ProductInventory;
use App\Entity\Document\ProductReceipt;
use App\Entity\Document\ProductTransfer;
use App\Entity\Shop\ShopOrder;
use App\Entity\Reference\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\QueryBuilder;
use App\Filter\BaseFilter;

/**
 * Фильтрация по товарам на всех страницах склада
 *
 * Class DocumentStockFilter
 */
class DocumentStockFilter extends BaseFilter
{
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return  in_array($entityClass, [ProductReceipt::class, ProductInventory::class, ProductTransfer::class, ShopOrder::class])
            && $this->request->query->get('search');
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     * @throws MappingException
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $keyword = mb_strtolower($this->request->query->get('search'));

        $queryBuilder
            ->leftJoin(
                $mainAlias.'.documentProducts',
                'dp2'
            )
            ->leftJoin(
                Product::class,
                'p',
                'WITH',
                'p.id = dp2.product'
            );

        $classMetadata =  $queryBuilder->getEntityManager()->getClassMetadata(Product::class);

        foreach ($classMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);

            if (in_array($fieldMapping['type'], [Types::STRING, Types::TEXT], true)) {
                $queryBuilder->orWhere(
                    $queryBuilder->expr()->like(sprintf('LOWER(p.%s)', $fieldMapping['fieldName']), ':keyword')
                );
            }
        }

        $queryBuilder->setParameter('keyword', "%$keyword%");
    }
}
