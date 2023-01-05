<?php

namespace App\Filter;

use App\Entity\Document\DocumentHistory;
use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\QueryBuilder;
use App\Filter\BaseFilter;

/**
 * Class DocumentProductSearch
 */
class DocumentProductSearch extends BaseFilter
{
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return  in_array($entityClass, [DocumentHistory::class, ProductStock::class])
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
                Product::class,
                'p',
                'WITH',
                'p.id = '.$mainAlias.'.product'
            )
        ;

        $classMetadata =  $queryBuilder->getEntityManager()->getClassMetadata(Product::class);
        $orWhere = $queryBuilder->expr()->orX();
        foreach ($classMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);

            if (in_array($fieldMapping['type'], [Types::STRING, Types::TEXT], true)) {
                $exp = $queryBuilder->expr()->like(sprintf('LOWER(p.%s)', $fieldMapping['fieldName']), ':keyword');
                $orWhere->add(
                    $exp
                );
            }
        }
        $queryBuilder->andWhere($orWhere);
        $queryBuilder->setParameter('keyword', "%$keyword%");
    }
}
