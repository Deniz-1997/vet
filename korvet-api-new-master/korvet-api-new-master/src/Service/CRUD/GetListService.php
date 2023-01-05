<?php

namespace App\Service\CRUD;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DependenciesInterface;
use App\Interfaces\FilterInterface;
use App\Model\CommonModel;
use App\Model\Env;
use App\Service\HandlerException\Validation\ValidationException;
use DateTime;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\EventDispatcher\GetListEvent;
use App\Filter\BaseFilter;
use App\Filter\DeletedFilter;
use App\Exception\ApiException;
use App\Packages\Response\BinaryFileResponse;
use App\Packages\Response\BaseResponse as ApiResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use function array_key_exists;
use function call_user_func_array;
use function count;
use function in_array;
use function is_array;
use function is_string;
use function json_decode;

/**
 * Class GetListService
 */
class GetListService extends AbstractService
{
    const LOGIC_OR = 'OR';

    const LOGIC_AND = 'AND';

    const LOGIC_OPERATORS_ALLOW = [
        self::LOGIC_OR,
        self::LOGIC_AND,
    ];

    const DEFAULT_ALIAS = 'e';

    /**
     * @var int
     */
    protected int $maxItems = 100;

    /**
     * @var FilterInterface[]
     */
    protected $filterHandlers;

    /**
     * @var array
     */
    private array $allowedAttributesCache;

    /**
     * @var ClassMetadata[]
     */
    private ?array $classMetadataCache = null;

    /**
     * @var ApiResponse
     */
    private $response;

    /**
     * @var string
     */
    private string $entityName;

    /**
     * @var string
     */
    private string $alias = self::DEFAULT_ALIAS;

    /**
     * @var ClassMetadata
     */
    private ClassMetadata $metadata;

    /**
     * @var QueryBuilder
     */
    private QueryBuilder $builder;

    /**
     * @var ExpressionBuilder
     */
    private ExpressionBuilder $expr;

    /**
     * @var array
     */
    private array $filter = [];

    /**
     * @var ?array
     */
    private ?array $orderBy = null;

    /**
     * @var array
     */
    private array $fields = [];

    /**
     * @var int
     */
    private int $offset = 0;

    /**
     * @var int
     */
    private int $limit = 20;

    /**
     * @var string
     */
    private string $logic = self::LOGIC_AND;

    /**
     * @var array
     */
    private array $fieldsOperator = [];

    /**
     * @var Criteria
     */
    private Criteria $criteria;

    /**
     * @var bool
     */
    private bool $isValidQuery = true;

    /**
     * @var Reader
     */
    private Reader $annotationReader;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * GetListService constructor.
     *
     * @param iterable $filterHandlers
     * @param DependenciesInterface $dependencies
     * @param DeletedFilter $deletedFilter
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param BinaryFileResponse $binaryFileResponse
     */
    public function __construct(
        iterable $filterHandlers,
        DependenciesInterface $dependencies,
        DeletedFilter $deletedFilter,
        Reader $annotationReader,
        BinaryFileResponse $binaryFileResponse
    ) {
        $this->filterHandlers = $filterHandlers;
        $this->response = $dependencies->getRequest()->query->get('download') ?
            $binaryFileResponse : $dependencies->getResponse();
        $this->serializer = $dependencies->getSerializer();
        $this->annotationReader = $annotationReader;

        parent::__construct($dependencies, $deletedFilter);
    }

    /**
     * @param Request $request
     * @param         $objectName
     */
    protected function init(Request $request, $objectName)
    {
        $this->entityName = $objectName;
        $this->metadata = $this->dependencies->getOm()->getClassMetadata($this->entityName);
        $this->createExpr();
        $this->createCriteria();
        $this->createQueryBuilder();
        // число записей на странице
        $this->orderBy = json_decode($request->query->get('order'), true);
        if (is_null($this->orderBy)) {
            $this->orderBy = [
                'id' => 'DESC'
            ];
        }
        $this->fields = json_decode($request->query->get('fields'), true) ?? [];
        $this->filter = json_decode($request->query->get('filter'), true) ?? [];
        $this->limit = (int)$request->query->get('limit', $this->limit);
        if ($this->limit > $this->maxItems && !$request->query->get('download')) {
            $this->limit = $this->maxItems;
        }
        if ($logic = $this->getLogicOperator($this->filter)) {
            $this->logic = $logic;
        }
        $this->offset = (int)$request->query->get('offset', 0);
    }

    /**
     * @param array $filter
     */
    public function getLogicOperator(array $filter)
    {
        if (isset($filter['LOGIC']) && in_array($filter['LOGIC'], self::LOGIC_OPERATORS_ALLOW, true)) {
            $this->logic = $filter['LOGIC'];
        }
    }

    /**
     * @param array|null $filters
     * @param array $operators
     * @return array
     */
    public function getFieldsOperator(?array &$filters, array &$operators = []): array
    {
        if (!$filters) {
            $filters = $this->filter;
        }

        foreach ($filters as $column => $filter) {
            if (is_string($column)) {
                if ($op = $this->getOperator($column)) {
                    $operators[$this->clearValue($column)] = $op;
                }
            }
            if (is_array($column)) {
                $this->getFieldsOperator($column, $operators);
            }
            if (is_array($filter)) {
                $this->getFieldsOperator($filter, $operators);
            }
        }

        return $operators;
    }

    /**
     * @param Request $request
     * @param string $objectName
     * @param array $context
     * @param bool $usageDoctrinePaginator
     *
     * @return ApiResponseInterface
     * @throws ApiException
     * @throws MappingException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws QueryException
     * @throws ReflectionException
     */
    public function getList(Request $request, string $objectName, array $context = [], bool $usageDoctrinePaginator = false): ApiResponseInterface
    {
        // init property $this
        $this->init($request, $objectName);
        $beforeEvent = $this->beforeFilter($request, $context);
        // before Query event
        if ($responseEvent = $beforeEvent->getResponse()) {
            $response = $this->getDependencies()->getResponse()
                ->setResponse($responseEvent);
            if ($beforeEvent->getHttpCode()) {
                $response->setHttpResponseCode($beforeEvent->getHttpCode());
            }
            return $response;
        }

        // apply filter handlers - configurable option
        foreach ($this->filterHandlers as $filterHandler) {
            if ($filterHandler instanceof BaseFilter) {
                $filterHandler->setQueryBuilder($this->builder);
            }

            if ($filterHandler->support($this->entityName, $this->filter)) {
                $filterHandler->handle($this->builder, $this->entityName, self::DEFAULT_ALIAS, $this->filter);
            }
        }

        if (property_exists($this->entityName, 'deleted')) {
            // Get all items including deleted=true
            // Request example - {filter: {deleted: '*'}}
            if (!empty($this->filter['deleted']) && $this->filter['deleted'] == '*') {
                unset($this->filter['deleted']);
            } else {
                $this->criteria->andWhere($this->expr->eq('deleted', false));
            }
        }

        // create criteria
        if (count($this->filter)) {
            $arExpressions = [];
            if ($expr = $this->getListFilters($this->filter, $arExpressions, $this->logic)) {
                switch ($this->logic) {
                    case self::LOGIC_AND:
                        $this->criteria->andWhere($expr);
                        break;
                    case self::LOGIC_OR:
                        $this->criteria->orWhere($expr);
                        break;
                    default:
                        count($arExpressions) > 1 ? $this->criteria->andWhere($expr) : $this->criteria->where($expr);
                }
            }
        }

        // offset | limit
        $this->criteria->setFirstResult($this->offset);
        $this->criteria->setMaxResults($this->limit);

        // Order by
        $this->createOrderByCondition();

        // Query
        $this->builder->addCriteria($this->criteria);

        $query = $this->builder;

        /** @var Join[] $joins */
        $joins = $query->getDQLPart('join');

        if ($usageDoctrinePaginator) {
            $paginator = new Paginator($query, !empty($joins));
            $arItems = iterator_to_array($paginator->getIterator());
            $totalCount = count($paginator);
        } else {
            $builderCount = clone $this->builder;
            $arItems = $this->builder->getQuery()->getResult();
            $totalCount = $builderCount->select('COUNT(DISTINCT ' . self::DEFAULT_ALIAS . ')')
                ->resetDQLPart('orderBy')
                ->setMaxResults(null)
                ->setFirstResult(null)
                ->getQuery()
                ->getSingleScalarResult();
        }

        $allowedAttributes = $this->getDtoClass() ? [] : $this->getAllowedAttributes($objectName);
        $contextAttributes = $context['attributes'] ?? [];
        $requestAttributes = $this->fields;
        $context['attributes'] = !empty($this->fields) ? $this->fields : array_merge($allowedAttributes, $contextAttributes, $requestAttributes);
        if (!$context['attributes']) {
            unset($context['attributes']);
        }

        // After Query event
        $eventName = $this->getEventName($this->entityName, 'getList');
        $event = $this->generateEvent($arItems, [EventRequest::AFTER_PROCESS . ucfirst($eventName)], ['filter' => $this->filter]);

        if ($dtoObjectName = $this->getDtoClass()) {
            $items = [];
            foreach ($event->getData() as $item) {
                $dto = $this->dependencies->getDtoFactory()->getDTO($dtoObjectName);
                $dto->loadEntity($item);
                $items[] = $dto;
            }
        } else {
            $items = $event->getData();
        }

        $globalGetListEvent = new GetListEvent($items, $this->entityName);
        $this->dependencies->getDispatcher()->dispatch($globalGetListEvent, GetListEvent::NAME);
        $items = $globalGetListEvent->getItems();

        switch ($request->get('download')) {
            case 'xlsx':
                $response = $this->prepareXlsResponse($items, $context);
                break;

            case 'pdf':
                $response = $this->preparePdfResponse();
                break;

            default:
                $response = [
                    'totalCount' => $totalCount,
                    'countItems' => count($items),
                    'items' => $items,
                ];
                break;
        }

        /** @var QueryBuilder $qb */
        $this->response
            ->setSerializationContext($context)
            ->setResponse($response);

        return $this->response;
    }

    /**
     * @param array|null $items
     * @param array|null $context
     * @return bool|string
     * @throws ReflectionException
     */
    private function prepareXlsResponse(?array $items, ?array $context)
    {
        $entityBaseName = (new ReflectionClass($this->entityName))->getShortName();
        $this->response->setHeaders([
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $entityBaseName . '.xlsx"',
        ]);

        $writer = $this->prepareXlsData($items, $context);
        $fileName = $entityBaseName . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);


        return $temp_file;
    }

    /**
     * @param array|null $items
     * @param array|null $context
     * @return Xlsx
     * @throws ReflectionException
     */
    private function prepareXlsData(?array $items, ?array $context)
    {
        $spreadsheet = new Spreadsheet();
        $entityBaseName = (new ReflectionClass($this->entityName))->getShortName();
        $sheet = $spreadsheet->getActiveSheet();
        $xlsData = $item = [];

        // prepare Xlsx data
        foreach ($this->serializer->normalize($items, 'array', $context) as $r => $item) {
            // $item = $this->serializer->normalize($item, 'array', $context);
            foreach ($item as $attributeName => $attributeValue) {
                if (is_array($attributeValue)) {

                    // prepare field value for subentities
                    foreach (['title', 'name', 'сode', 'id'] as $entityName) {
                        if (array_key_exists($entityName, $attributeValue)) {
                            $item[$attributeName] = $attributeValue[$entityName];

                            break;
                        }
                    }

                    // skip values for multiple entities or not found fields
                    if (is_array($item[$attributeName])) {
                        $item[$attributeName] = '';
                    }
                }
            }

            $xlsData[] = $item;
        }

        //set headers
        array_unshift($xlsData,  array_keys($item));

        $sheet->fromArray(
            $xlsData
        );

        $sheet->setTitle($entityBaseName);

        return new Xlsx($spreadsheet);
    }

    private function preparePdfResponse()
    {
        throw new ApiException('method not implmented');
    }

    private function getAllowedAttributes(string $entityName, $nestedLevel = 1): array
    {
        if (isset($this->allowedAttributesCache[$entityName])) {
            return $this->allowedAttributesCache[$entityName];
        }

        if (!$this->classMetadataCache) {
            $metadata = $this->dependencies->getOm()->getMetadataFactory()->getAllMetadata();
            /** @var ClassMetadata $classMeta */
            foreach ($metadata as $classMeta) {
                $this->classMetadataCache[$classMeta->getName()] = $classMeta;
            }
        }

        $allowedAttributes = $this->classMetadataCache[$entityName]->getFieldNames();
        $embeddedClasses = $this->classMetadataCache[$entityName]->embeddedClasses ?: [];
        $allowedAttributes = array_merge($allowedAttributes, array_keys($embeddedClasses));
        if ($nestedLevel == 3) {
            return $allowedAttributes;
        }

        $associationMappings = $this->classMetadataCache[$entityName]->getAssociationMappings();
        foreach ($associationMappings as $associationMapping) {
            $fieldName = $associationMapping['fieldName'];
            $targetEntity = $associationMapping['targetEntity'];
            $allowedAttributes[$fieldName] = $this->getAllowedAttributes($targetEntity, $nestedLevel + 1);
        }

        $reflectionClass = new ReflectionClass($entityName);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($this->annotationReader->getPropertyAnnotation($reflectionProperty, Groups::class)) {
                $allowedAttributes[] = $reflectionProperty->getName();
            }
        }

        $this->allowedAttributesCache[$entityName] = $allowedAttributes;

        return $allowedAttributes;
    }

    /**
     * Create Order by condition
     *
     * @return array
     */
    protected function createOrderByCondition(): array
    {
        $arOrder = [];
        if (is_array($this->orderBy)) {
            foreach ($this->orderBy as $property => $val) {
                if (isset($this->metadata->associationMappings[$property])) {
                    //                    See OrderByAssociationSubscriber class for this logic
                    //                    $keys = array_keys($val);
                    //                    $values = array_values($val);
                    //                    $relAlias = $property . $keys[0];
                    //
                    //                    $this->builder->leftJoin(self::DEFAULT_ALIAS.'.'.$property, $relAlias);
                    //
                    //                    $arOrder[$relAlias . '.' . $keys[0]] = strtolower($values[0]);
                } elseif ($this->isEmmedable($this->metadata, $property, $val)) {
                    foreach ($val as $prop => $order) {
                        $arOrder[$property . '.' . $prop] = strtolower($order);
                    }
                } else {
                    if (property_exists($this->entityName, $property)) {
                        if (!in_array(strtolower($val), ['asc', 'desc'], true)) {
                            $val = 'asc';
                        }
                        if (!array_key_exists($property, $arOrder)) {
                            $arOrder[$property] = strtolower($val);
                        }
                    }
                }
            }
        }
        if (!empty($arOrder)) {
            $this->criteria->orderBy($arOrder);
        }

        return $arOrder;
    }

    /**
     * @param array $arFilter
     * @param array $arExpressions
     * @param null $op
     *
     * @return Expression|null
     * @throws MappingException
     * @throws QueryException
     * @throws ReflectionException
     * @todo: Добавить проверки что колонка существует - иначе не применять фильтр
     *
     */
    protected function getListFilters(array $arFilter, array &$arExpressions, $op = null): ?Expression
    {
        $logic = $this->logic;

        if ($op && in_array($op, self::LOGIC_OPERATORS_ALLOW, true)) {
            $logic = $op;
        }
        foreach ($arFilter as $column => $filterValue) {
            $filterValueIsArray = is_array($filterValue);
            if ($filterValueIsArray === true && is_int($column)) {
                if (count($filterValue)) {
                    $this->getListFilters($filterValue, $arExpressions);
                }
                continue;
            }

            if ($this->isEmmedable($this->metadata, $column, $filterValue)) {
                /** @var QueryBuilder $emBuilder */
                if (is_array($filterValue)) {
                    $filterValue = $this->getDataFilter($arFilter[$column]);
                }
                foreach ($filterValue as $col => $val) {
                    if (is_string($col)) {
                        $compareOperator = $this->getOperator($col);
                    } else {
                        $compareOperator = $this->getOperator($val['column']);
                    }
                    $emCol = $this->clearValue($val['column']);
                    $rel = $this->clearValue($column);
                    $this->criteria->andWhere($this->getCondition($rel . '.' . $emCol, $val['value'], $compareOperator));
                }

                continue;
            } else {
                // search by field type json
                if (isset($this->metadata->fieldMappings[$column])) {
                    $type = $this->metadata->fieldMappings[$column]['type'];
                    if (in_array($type, ['json', 'jsonb'])) {
                        $arExpressions[] = $this->getLikeCondition($column, $filterValue);

                        continue;
                    }
                }

                preg_match('/(\W*)([a-zA-Z]+)/', $column, $match);
                list(, $type, $columnWithoutType) = $match;

                if ($filterValueIsArray === true && isset($this->metadata->associationMappings[$columnWithoutType])) {
                    /** добавляем условие по релейшенам сущности */
                    $this->createConditionWithRelation($columnWithoutType, $filterValue, $arExpressions);
                } else {
                    /** Добавляем условия выборки по основной сущности */
                    if (is_string($filterValue)) {
                        try {
                            $isDateCondition = $this->createFilterDateTime($filterValue, $columnWithoutType, $type);
                            if ($isDateCondition) {
                                continue;
                            }
                        } catch (Exception $exception) {
                        }
                    } elseif (is_array($filterValue)) {
                        if (isset($this->metadata->fieldMappings[$column])) {
                            $compareOperator = $this->getOperator($column) ?? $this->getOperator($filterValue) ?? '=';
                            $arExpressions[] = $this->getCondition($column, $filterValue, $compareOperator);
                            continue;
                        }
                        foreach ($filterValue as $col => $v) {
                            if (is_int($col)) {
                                $col = $column;
                            }
                            $op = $this->getOperator($col) ?? $op;
                            $col = $this->clearValue($col);
                            $v = $this->clearValue($v);
                            $arExpressions[] = $this->getCondition($col, $v, $op);

                            continue;
                        }
                    }
                    if (mb_strtoupper($column, 'utf8') === 'LOGIC') {
                        $logicOperator = mb_strtoupper($filterValue, 'utf8');
                        if (in_array($logicOperator, self::LOGIC_OPERATORS_ALLOW, true)) {
                            $logic = $logicOperator;
                        } else {
                            $logic = self::LOGIC_AND;
                        }
                        continue;
                    }

                    if ($this->metadata->getColumnName($columnWithoutType)) {
                        $arExpressions[] = $this->getCondition($columnWithoutType, $filterValue, $type);
                    }
                }
            }
        }
        if (!empty($arExpressions) && $this->isValidQuery) {
            if (!$logic) {
                $logic = $this->logic ?? self::LOGIC_AND;
            }
            $this->prepareExpressions($arExpressions);

            return call_user_func_array([$this->expr, $logic === self::LOGIC_OR ? 'orX' : 'andX'], $arExpressions);
        }

        return null;
    }

    /**
     * @param string $columnWithoutType
     * @param array|string|int $filterValue
     * @param null $operator
     * @param bool $isIgnoreLike
     * @return Comparison|null
     */
    protected function getCondition(string $columnWithoutType, $filterValue, $operator = null, $isIgnoreLike = false): ?Comparison
    {
        $expr = null;
        if (!empty($operator) && !$isIgnoreLike) {
            $expr = $this->getLikeCondition($columnWithoutType, $filterValue, $operator);
        }
        if (!$operator && !$expr) {
            $operator = '=';
        }
        $columnWithoutType = $this->clearValue($columnWithoutType);
        $filterValue = $this->clearValue($filterValue);

        $likeAutocomplete = Env::getenv('FILTER_LIKE_AUTOCOMPLETE') == 1;
        if (is_string($filterValue)) {
            $likeAutocomplete = $likeAutocomplete && substr($filterValue, 0, 1) != '%' && substr($filterValue, -1) != '%';
        }

        switch ($operator) {
            case '=':
                if (is_array($filterValue)) {
                    $expr = $this->expr->in($columnWithoutType, $filterValue);
                } else {
                    $expr = $this->expr->eq($columnWithoutType, $filterValue);
                }
                break;
            case '!':
                if (!is_array($filterValue)) {
                    if (is_bool($filterValue)) {
                        switch ($filterValue) {
                            case true:
                                $expr = $this->expr->lt($columnWithoutType, $filterValue);
                                break;
                            case false:
                                $expr = $this->expr->gt($columnWithoutType, $filterValue);
                                break;
                        }
                    } else {
                        $expr = $this->expr->neq($columnWithoutType, $filterValue);
                    }
                } else {
                    foreach ($filterValue as $val) {
                        $expr = $this->getCondition($columnWithoutType, $val, $operator);
                    }
                }
                break;
            case '!=':
                if (is_array($filterValue)) {
                    $expr = $this->expr->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $this->expr->gt($columnWithoutType, $filterValue);
                }
                break;
            case '>=':
                if (is_array($filterValue)) {
                    $expr = $this->expr->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $this->expr->gte($columnWithoutType, $filterValue);
                }
                break;
            case '<=':
                if (is_array($filterValue)) {
                    $expr = $this->expr->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $this->expr->lte($columnWithoutType, $filterValue);
                }
                break;
            case '>':
                if (is_array($filterValue)) {
                    $expr = $this->expr->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $this->expr->gt($columnWithoutType, $filterValue);
                }
                break;
            case '<':
                if (!is_array($filterValue)) {
                    $expr = $this->expr->lt($columnWithoutType, $filterValue);
                } else {
                    foreach ($filterValue as $val) {
                        $expr = $this->getCondition($columnWithoutType, $val, $operator);
                    }
                }
                break;
            case '%':
                if ($likeAutocomplete) {
                    $filterValue = '%' . $filterValue . '%';
                }

                $this->builder
                    ->andWhere(sprintf('%s.%s LIKE :%s', $this->alias, $columnWithoutType, $columnWithoutType . '_value'))
                    ->setParameter($columnWithoutType . '_value', $filterValue);
                $expr = null;
                break;
            case '~':
                if ($likeAutocomplete) {
                    $filterValue = '%' . $filterValue . '%';
                }

                $this->builder
                    ->andWhere(sprintf('LOWER(%s.%s) LIKE :%s', $this->alias, $columnWithoutType, $columnWithoutType . '_value'))
                    ->setParameter($columnWithoutType . '_value', mb_strtolower($filterValue));
                $expr = null;
                break;
            case '~!':
                if ($likeAutocomplete) {
                    $filterValue = '%' . $filterValue . '%';
                }

                $this->builder
                    ->andWhere(sprintf('LOWER(%s.%s) NOT LIKE :%s', $this->alias, $columnWithoutType, $columnWithoutType . '_value'))
                    ->setParameter($columnWithoutType . '_value', mb_strtolower($filterValue));
                $expr = null;
                break;
        }

        return $expr;
    }

    /**
     * @param string $columnWithoutType
     * @param array|string $filterValue
     * @param string|null $operator
     *
     * @return Comparison|null
     */
    public function getLikeCondition(string $columnWithoutType, $filterValue, $operator = null): ?Comparison
    {
        $expr = null;
        if ($operator === '~' && isset($this->metadata->fieldMappings[$columnWithoutType])) {
            // Для поиска посредством оператора LIKE
            $refProperty = $this->metadata->fieldMappings[$columnWithoutType];

            if (isset($refProperty['type'])) {
                switch ($refProperty['type']) {
                    case 'string':
                        if (is_string($filterValue)) {
                            $firstChar = substr($filterValue, 0, 1);
                            $lastChar = substr($filterValue, -1);
                            $filterValue = trim($filterValue, '%');
                            if ($firstChar === '%' && $lastChar === '%') {
                                $expr = $this->expr->contains($columnWithoutType, $filterValue);
                            } elseif ($firstChar === '%') {
                                $expr = $this->expr->endsWith($columnWithoutType, $filterValue);
                            } elseif ($lastChar === '%') {
                                $expr = $this->expr->startsWith($columnWithoutType, $filterValue);
                            } else {
                                $expr = $this->expr->eq($columnWithoutType, $filterValue);
                            }
                        } else {
                            $expr = $this->expr->eq($columnWithoutType, $filterValue);
                        }
                        break;
                    case 'json':
                    case 'jsonb':
                        $rsm = new ResultSetMapping();
                        $rsm->addScalarResult('id', 'id');
                        $columnWithoutType = $this->clearValue($columnWithoutType);

                        $em = $this->dependencies->getOm();
                        $t = $this->metadata->table['name'];
                        $sql = 'SELECT * FROM ' . $t . ' _t0';
                        if (is_array($filterValue)) {
                            $filterValue = $this->clearValue(json_encode($filterValue));
                        } else {
                            $filterValue = $this->clearValue((string)$filterValue);
                        }
                        $sql .= ' WHERE _t0.' . $this->metadata->columnNames[$columnWithoutType] . '::jsonb @> \'' . $filterValue . '\'';
                        $query = $em->createNativeQuery($sql, $rsm);
                        $query->getArrayResult();
                        $res = $query->getResult();
                        $list = [];
                        foreach (array_values($res) as $k => $id) {
                            $list[$id['id']] = $id['id'];
                        }

                        $expr = $this->getCondition('id', array_values($list), '=');
                        break;
                }
            }
        }

        return $expr;
    }

    /**
     * @param string $column
     * @param array|null $filterValue
     * @param array $arExpressions
     * @throws MappingException
     * @throws QueryException
     * @throws ReflectionException
     */
    protected function createConditionWithRelation(string $column, ?array $filterValue, array &$arExpressions)
    {
        $column = $this->clearValue($column);
        $relationClass = $this->metadata->associationMappings[$column]['targetEntity'];
        $metadataRelation = $this->dependencies->getOm()->getClassMetadata($relationClass);
        $op = $this->getOperator($filterValue) ?? '=';
        $dataFilter = $this->getDataFilter($filterValue);
        // формируем критерию поиска для выборки связанной сущности
        foreach ($dataFilter as $rel => $columns) {
            // Emmedable entity
            if (is_string($rel) && $this->isEmmedable($metadataRelation, $rel, $columns)) {
                $criteria = Criteria::create();
                /** @var QueryBuilder $emBuilder */
                foreach ($columns as $emColumn) {
                    $compareOperator = $this->getOperator($emColumn['column']);
                    $emCol = $this->clearValue($emColumn['column']);
                    $rel = $this->clearValue($rel);
                    $criteria->andWhere($this->getCondition($rel . '.' . $emCol, $emColumn['value'], $compareOperator));
                }
                $data = $this->dependencies->getOm()
                    ->getRepository($metadataRelation->getName())
                    ->createQueryBuilder('em_www')
                    ->distinct(true)
                    ->addCriteria($criteria)
                    ->groupBy($rel . '.' . $emCol)
                    ->getQuery()
                    ->getResult();

                if (!empty($data)) {
                    $this->criteria->andWhere($this->getCondition($column, $data, '='));
                }
            } else {
                // Остальные случаи
                if (isset($columns['column'])) {
                    $columns = [$columns];
                }
                foreach ($columns as $col => $value) {
                    if (isset($value['column'])) {
                        // получаем связанную сущность $column
                        if (isset($metadataRelation->associationMappings[$rel])) {
                            $dataRel = null;
                            $relEntity = $metadataRelation->associationMappings[$rel]['targetEntity'];
                            if ($metadataRelation->associationMappings[$rel]['type'] === ClassMetadata::MANY_TO_ONE) {
                                $dataRel = $this->dependencies->getOm()->getRepository($relEntity)->findOneBy([$value['column'] => $value['value']]);
                            } elseif ($metadataRelation->associationMappings[$rel]['type'] === ClassMetadata::ONE_TO_MANY) {
                                $dataRel = $this->dependencies->getOm()->getRepository($relEntity)->findBy([$value['column'] => $value['value']]);
                            }
                            if ($dataRel) {
                                $criteria = Criteria::create();
                                $criteria->andWhere($this->getCondition($rel, $dataRel, $op));
                                $data = $this->dependencies->getOm()->getRepository($metadataRelation->name)->createQueryBuilder('www_' . $column)
                                    ->addCriteria($criteria)
                                    ->getQuery()
                                    ->getResult();

                                $this->criteria->andWhere($this->getCondition($column, $data, $op));

                                continue;
                            }
                        } elseif (isset($this->metadata->associationMappings[$column])) {
                            $dataRel = null;
                            $relEntity = $this->metadata->associationMappings[$column]['targetEntity'];
                            $queryBuilder = $this->dependencies->getOm()->getRepository($relEntity)
                                ->createQueryBuilder('x_');
                            $callback = $this->metadata->associationMappings[$column]['type'] === ClassMetadata::ONE_TO_MANY ? 'findBy' : 'findOneBy';
                            if ($expr = $this->createFilterDateTime($value['value'], $value['column'], $op,  true)) {
                                $criteria = Criteria::create();
                                $criteria->andWhere($expr);
                                $queryBuilder->addCriteria($criteria)
                                    ->setFirstResult($this->offset)
                                    ->setMaxResults($this->limit);
                                $dataRel = $queryBuilder->getQuery()->getResult();
                            } else {
                                if (!in_array($value['column'], $metadataRelation->fieldNames)) {
                                    continue;
                                }
                                $columnCond = $this->clearValue($value['column']);
                                $columnCondVal = $this->clearValue($value['value']);
                                $dataRel = $this->dependencies->getOm()->getRepository($relEntity)->$callback([$columnCond => $columnCondVal]);
                            }
                            $this->criteria->andWhere($this->getCondition($column, $dataRel, $op));
                        }
                    }
                }
            }
        }
    }

    /**
     * @param ClassMetadata $metadataRelation
     * @param string $rel
     * @param array|string $columns
     *
     * @return boolean
     */
    private function isEmmedable($metadataRelation, string $rel, $columns): bool
    {
        if (isset($metadataRelation->embeddedClasses[$rel])) {
            return true;
        }
        if (!is_array($columns)) {
            return isset($metadataRelation->embeddedClasses[$columns]);
        }

        foreach ($columns as $column) {
            if (isset($column['column']) && isset($metadataRelation->embeddedClasses[$column['column']])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array|null $filterValue
     * @return array|false
     */
    public function getDataFilter(array $filterValue)
    {
        $items = [];
        foreach ($filterValue as $column => $value) {
            if (!is_array($value)) {
                $items[] = [
                    'column' => $column,
                    'value' => $value,
                ];
            } else {
                $items[$column] = $this->getDataFilter($value);

                // todo Проверить работу рекурсии и если все ок то удалить закомиченный блок
                //                foreach ($value as $col => $val) {
                //                    $items[$column][] = [
                //                        'column' => $col,
                //                        'value' => $val,
                //                    ];
                //                }
            }
        }

        return $items;
    }

    /**
     * @param array $arExpressions
     */
    protected function prepareExpressions(array &$arExpressions): void
    {
        foreach ($arExpressions as $k => $v) {
            if (!$v) {
                unset($arExpressions[$k]);
            }
        }
    }

    /**
     * @param Request $request
     * @param array $context
     *
     * @return EventRequest
     * @throws ApiException
     */
    private function beforeFilter(Request $request, array $context): EventRequest
    {
        $this->validateFilter($request->query->all());
        $eventName = $this->getEventName($this->entityName, 'getList');
        $data = ['query' => $request->query->all(), 'filter' => $this->filter, 'objectName' => $this->entityName, 'context' => $context];
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($data, [EventRequest::BEFORE_PROCESS . $eventName]);
        $this->assertEvent($beforeEvent);

        return $beforeEvent;
    }

    /**
     * @param array $filters
     *
     * @throws ValidationException
     */
    private function validateFilter(array $filters)
    {
        $modelFilter = $this->dependencies->getSerializer()->deserialize(json_encode($filters), CommonModel::class, self::FORMAT_JSON);
        $this->dependencies->getValidator()->validate($modelFilter);
    }

    /**
     * @param array|string $column
     *
     * @return string|null
     */
    private function getOperator($column): ?string
    {
        $op = null;
        if (is_array($column)) {
            foreach ($column as $name => $value) {
                if (preg_match('/(\=|\<\=|\>\=|\!\=|<|>|\!)/', $name, $m)) {
                    $op = $m[1];
                }
                if (is_array($value)) {
                    $op = $this->getOperator($value);
                } elseif (is_string($value)) {
                    $op = $this->getOperator($value);
                }
            }
        } else {
            if (preg_match('/(\=|\<\=|\>\=|\!\=|<|>|\!)/', $column, $m)) {
                $op = $m[1];
            }
        }

        return $op;
    }

    /**
     * @param string $operator
     * @param string $filterValue
     * @param string $columnWithoutType
     * @param array $arExpressions
     * @param boolean $returnExpr
     *
     * @return bool|Expression|void
     * @todo : в данный момент не работает `!=` и эквивалентный
     * ему < + > в фильтрах
     * >  18.07.2017: 18.07.2017 23:59:59
     * <= 18.07.2017: 18.07.2017 23:59:59
     * <  18.07.2017: 18.07.2017 00:00:00
     * >= 18.07.2017: 18.07.2017 00:00:00
     * =  18.07.2017: >=18.07.2017 00:00:00 - <=18.07.2017 23:59:59
     * != 18.07.2017: <18.07.2017 00:00:00 - >18.07.2017 23:59:59
     */
    private function createFilterDateTime(string $filterValue, string $columnWithoutType, string $operator = '=', bool $returnExpr = false)
    {
        $dateFormats = ['d.m.Y', 'Y-m-d'];
        $dateTimeFormats = ['d.m.Y H:i:s', 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', DateTime::RFC3339];
        $formats = array_merge($dateFormats, $dateTimeFormats);

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $filterValue);
            if ($date instanceof DateTime && in_array($format, $dateFormats)) {
                switch ($operator) {
                    case '<':
                        $date->setTime(00, 00, 00);
                        break;
                    case '<=':
                        $date->setTime(23, 59, 59);
                        break;
                    case '>':
                        $date->setTime(23, 59, 59);
                        break;
                    case '>=':
                        $date->setTime(0, 0);
                        break;
                    case '=':
                        $date->setTime(00, 00, 00);
                        break;
                    case  '!=':
                        $date->setTime(00, 00, 00);
                        break;
                }
            }
            if ($date instanceof DateTime) {
                $columnWithoutType = $this->clearOperatorColumn($columnWithoutType);
                try {
                    $expr = $this->getCondition($columnWithoutType, $date, $operator, true);
                    if ($returnExpr) {
                        return $expr;
                    }
                    $this->criteria->andWhere($expr);
                } catch (\Throwable $ex) {
                    return false;
                }
                return true;
            }
        }
    }

    /**
     * @return EntityRepository|ObjectRepository
     */
    public function getRepository()
    {
        return $this->dependencies->getOm()->getRepository($this->entityName);
    }

    /**
     * Create Criteria search
     */
    private function createCriteria()
    {
        $this->criteria = Criteria::create();
    }

    /**
     * Create builder expression
     */
    private function createExpr()
    {
        $this->expr = Criteria::expr();
    }

    /**
     * Create builder expression
     */
    private function createQueryBuilder()
    {
        $this->builder = $this->getRepository()->createQueryBuilder($this->alias);
    }

    /**
     * @param array|string $filterValue
     *
     * @return array|string
     */
    private function clearValue($filterValue)
    {
        $findBy = [];
        if (is_string($filterValue)) {
            return $this->clearOperatorColumn($filterValue);
        }
        if (is_array($filterValue)) {
            foreach ($filterValue as $k => $v) {
                if (is_string($k)) {
                    $findBy[$this->clearOperatorColumn($k)] = $v;
                }
            }
        }

        if (empty($findBy)) {
            $findBy = $filterValue;
        }

        return $findBy;
    }

    /**
     * @param string $column
     *
     * @return string|null
     */
    private function clearOperatorColumn(string $column): ?string
    {
        return preg_replace('/(\=|\<\=|\>\=|\!\=|<|>|\!)/', '', $column);
    }

    /**
     * @return array
     */
    public function getFilter(): ?array
    {
        return $this->filter;
    }
}
