<?php

namespace App\Controller\CRUD;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Interfaces\ApiControllerInterface;
use App\Service\CRUD\GetListService;
use App\Service\SerializationContextFetcher;
use App\Exception\ApiException;
use App\Packages\Response\Async\AsyncResponse;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Trait GetListTrait
 */
trait GetListTrait
{
    /**
     * @Route("/", methods={"GET"})
     * @Operation("list")
     * @SWG\Get(
     *     @SWG\Parameter(
     *          name="search",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Строка для поиска по всем полям сущности типа string и text"
     *     ),
     *     @SWG\Parameter(
     *          name="filter",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Json-объект с фильтром записей вида {&quot;column_name&quot;: &quot;value&quot;}
 * Перед column_name можно указывать один из операторов: =, !=, &gt;, &gt;=, &lt;, &lt;=.
 * Без указания оператора используется сравнение LIKE.
 * Поле value может быть массивом, в этом случае происходит проверка на вхождение значения в массив. Если указать оператор ! перед column_name - то проверка будет на НЕ вхождение значения в массив.
 * При передаче нескольких условий они отрабатывают по И. Для изменения логики в фильтре надо передать значение OR с ключем LOGIC &quot;LOGIC&quot;: &quot;OR&quot;.

Пример сложного фильтра:
{&quot;LOGIC&quot;: &quot;OR&quot;, &quot;0&quot;: {&quot;!id&quot;: 1, &quot;deleted&quot;: true}, &quot;1&quot;: {&quot;=id&quot;: 10}}
"
     *     ),
     *     @SWG\Parameter(
     *          name="fields",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Json-объект со списком отбираемых полей, которые надо вывести в ответе (по умолчанию все)
Пример:
{&quot;0&quot;:&quot;id&quot;,&quot;1&quot;:&quot;number&quot;,&quot;status&quot;:[&quot;id&quot;,&quot;name&quot;,&quot;code&quot;]}
"
     *     ),
     *      @SWG\Parameter(
     *          name="order",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Json-объект с сортировкой записей вида {column_name: &quot;DESС&quot;}"
     *     ),
     *      @SWG\Parameter(
     *          name="offset",
     *          in="query",
     *          @SWG\Schema(
     *              type="integer"
     *          ),
     *          description="Начальная запись в списке"
     *     ),
     *      @SWG\Parameter(
     *          name="limit",
     *          in="query",
     *          @SWG\Schema(
     *              type="integer"
     *          ),
     *          description="Число записей в списке"
     *     ),
     *      @SWG\Parameter(
     *          name="download",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Режим скачивания списка как файл. Если передать значение xlsx - произойдет скачивание файла в формате MS Excel, при этом параметр max limit будет проигнорирован."
     *     ),
     *      @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         @SWG\JsonContent(),
     *         description="Запрошенный объект не найден, но запрос на его создание принят в обработку.",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *      @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     * @param RequestStack                $request
     * @param GetListService              $service
     * @param SerializationContextFetcher $serializationContextFetcher
     *
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\ORM\Mapping\MappingException
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \ReflectionException
     */
    public function getListAction(RequestStack $request, GetListService $service,
                                  SerializationContextFetcher $serializationContextFetcher): Response
    {
        if ($this instanceof ApiControllerInterface) {
            $serializationContext = $this->getSerializationContext('getList');
        } else {
            $serializationContext = [];
        }

        $entityClass = $this instanceof DynamicEntityClassControllerInterface ? $this->getEntityClass() : self::ENTITY_CLASS;

        $usageDoctrinePaginator = defined(get_class($this).'::USAGE_DOCTRINE_PAGINATOR') && self::USAGE_DOCTRINE_PAGINATOR;
        $isDefinedDtoClass = defined(get_class($this).'::DTO_CLASS');
        if ($groups = $serializationContextFetcher->getSerializationGroups('list', $isDefinedDtoClass ? self::DTO_CLASS : $entityClass)) {
            if (!isset($serializationContext['groups'])) {
                $serializationContext['groups'] = [];
            }

            $serializationContext['groups'] = array_merge($serializationContext['groups'], $groups);
        }

        if ($isDefinedDtoClass) {
            $service->setDtoClass(self::DTO_CLASS);
        }

        $isOldDB = defined(get_class($this).'::CONNECTION');

        if($isOldDB){
            $service->getDependencies()->setIsOldBD(self::CONNECTION === 'cas');
        }

        $result = $service->getList($request->getCurrentRequest(), $entityClass, $serializationContext, $usageDoctrinePaginator);

        return $result->send();
    }
}
