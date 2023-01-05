<?php

namespace App\Filter;

use App\Entity\Reference\Station;
use App\Entity\ReportsData;
use App\Service\GetChildStationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SearchDataReportObjectFilter
 * @package App\Filter
 */
class SearchDataReportObjectFilter extends BaseFilter
{
    private  GetChildStationService $childStation;


    /**
     * BaseFilter constructor.
     *
     * @param ?RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, GetChildStationService $childStation)
    {
        parent::__construct($requestStack);
        $this->childStation = $childStation;


    }
    /**
     * @param string $entityClass
     * @param array $filtersRequest
     * @return bool
     */
    public function support(string $entityClass, array $filtersRequest): bool
    {
        return $entityClass === ReportsData::class &&
            $this->request->get('filter') &&
            isset(json_decode($this->request->get('filter'))->station->id);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityClass
     * @param string $mainAlias
     * @param array $filtersRequest
     */
    public function handle(QueryBuilder $queryBuilder, string $entityClass, string $mainAlias, array &$filtersRequest)
    {
        $stationId = json_decode($this->request->get('filter'))->station->id;
        $stationList = $this->childStation->getChildStation($stationId);
        $query = '';
        if (count($stationList)) {
            foreach ($stationList as $station) {
                if (isset(json_decode($this->request->get('filter'))->supervisedObjects->id)) {
                    $supervisedObjectId = json_decode($this->request->get('filter'))->supervisedObjects->id;
                    $query .= sprintf('( %s.station=%s and %s.supervisedObjects=%s) OR', $mainAlias, $station->getId(), $mainAlias, $supervisedObjectId);
                } else {
                    $query .= sprintf(' %s.station=%s OR', $mainAlias, $station->getId());
                }
            }
            $query = trim($query, 'OR');
        } else {
            $query .= sprintf(' %s.station=%s', $mainAlias, $stationId);
        }
        $queryBuilder->orWhere($query);
    }
}
