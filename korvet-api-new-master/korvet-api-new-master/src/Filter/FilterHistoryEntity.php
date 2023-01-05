<?php

namespace App\Filter;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\EntityResolver;
use Doctrine\ORM\QueryBuilder;

/**
 * Class FilterHistoryEntity
 */
class FilterHistoryEntity extends BaseFilter
{
    /**
     * @var EntityResolver
     */
    private EntityResolver $resolver;

    public function __construct(RequestStack $requestStack, EntityResolver $resolver)
    {
        $this->resolver = $resolver;
        parent::__construct($requestStack);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $entityClass
     * @param string       $mainAlias
     * @param array        $filtersRequest
     *
     * @throws \ReflectionException
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $aliasEntity = $this->request->get('aliasEntity');
        $id = $this->request->get('id');

        if (!$historyAnnotation = $this->resolver->resolve($aliasEntity)) {
            return;
        }

        $queryBuilder
            ->andWhere($mainAlias.'.objectId=:id') //HelperFilterData::getCondition($queryBuilder, $mainAlias.'.objectId', $id, '=', $mainAlias))
            ->andWhere($mainAlias.'.objectClass=:className') // HelperFilterData::getCondition($queryBuilder, $mainAlias.'.objectClass', $historyAnnotation->entity, '=', $mainAlias))
            ->setParameter('id', $id)
            ->setParameter('className', $historyAnnotation->entity)
        ;

        if ($historyAnnotation->relationField) {
            $entity = $queryBuilder->getEntityManager()->getRepository($historyAnnotation->entity)->find($id);
            foreach ($historyAnnotation->relationField as $property) {
                $getter = 'get'.ucfirst($property);
                if ($relationObject = $entity->$getter()) {
                    if (!$relationObject instanceof Collection) {
                        $reflClass = (new \ReflectionClass($relationObject));
                        $relClassShort = $reflClass->getShortName();
                        $id1 = $relationObject->getId();
                        $queryBuilder
                            ->orWhere($mainAlias.'.objectId=:objectId_'.$relClassShort.' AND '.$mainAlias.'.objectClass=:classNameRelation_'.$relClassShort)
                            ->setParameter('objectId_'.$relClassShort, $id1)
                            ->setParameter('classNameRelation_'.$relClassShort, $reflClass->getName())
                        ;
                    } else {
                        foreach ($entity->$getter() as $key => $object) {
                            $reflClass = (new \ReflectionClass($object));
                            $relClassShort2 = $reflClass->getShortName();
                            $id2 = $object->getId();
                            $queryBuilder
                                ->orWhere(
                                    $mainAlias.'.objectClass=:objectClass_'.$relClassShort2.$key. ' AND '.$mainAlias.'.objectId=:id2_'.$relClassShort2.$key
                                )
                                ->setParameter('objectClass_'.$relClassShort2.$key, $reflClass->getName())
                                ->setParameter('id2_'.$relClassShort2.$key, $id2);
                        }
                    }
                }
            }
        }
        $filtersRequest = [];
    }

    public function support(string $entityClass, array $filtersRequest):bool
    {
        return $entityClass === 'App\Entity\HistoryEntity' && (array_key_exists('=objectId', $filtersRequest) || $this->request->get('id'));
    }
}
