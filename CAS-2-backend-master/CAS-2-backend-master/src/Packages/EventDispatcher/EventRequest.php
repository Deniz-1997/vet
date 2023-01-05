<?php

namespace App\Packages\EventDispatcher;

use App\Interfaces\ApiServiceInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CRUD\AbstractService;
use App\Service\CRUD\AddItemService;
use App\Service\CRUD\GetItemService;
use App\Service\CRUD\GetListService;
use App\Service\CRUD\ReplaceItemService;
use App\Service\CRUD\UpdateItemService;

/**Pet
 * Class EventRequest
 */
class EventRequest extends Event
{
    const BEFORE_PROCESS_ENTITY = 'onBeforeProcessEntity';
    const BEFORE_PROCESS = 'onBeforeProcess';
    const AFTER_PROCESS = 'onAfterProcess';
    const BEFORE_SAVE_ENTITY = 'onBeforeSaveEntity';
    const AFTER_SAVE_ENTITY = 'onAfterSaveEntity';
    const BEFORE_DELETE_ENTITY = 'onBeforeDeleteEntity';
    const AFTER_DELETE_ENTITY = 'onAfterDeleteEntity';

    /**
     * Name event
     * @var string
     */
    public $name;
    /**
     * @var integer
     */
    private $httpCode = Response::HTTP_OK;
    /**
     * @var array
     */
    public $filter;
    /**
     * @var mixed|null
     */
    private $response = null;
    /**
     * @var AbstractService
     */
    private $vendor;
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var bool
     */
    public $isValid = true;
    /**
     * @var integer
     */
    public $queueId;

    /**
     * EventRequest constructor.
     *
     * @param ApiServiceInterface|AbstractService $vendor
     * @param mixed $data
     */
    public function __construct(ApiServiceInterface $vendor, $data = null)
    {
        $this->vendor = $vendor;
        $this->data = $data;
    }

    /**
     * @return ApiServiceInterface|AbstractService|GetListService|GetItemService|AddItemService|UpdateItemService|ReplaceItemService
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return EventRequest
     */
    public function setData($data):EventRequest
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     *
     * @return $this
     */
    public function setResponse($response): EventRequest
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param integer $id
     *
     * @return $this
     */
    public function setQueueId($id): EventRequest
    {
        $this->queueId = $id;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getQueueId():?int
    {
        return $this->queueId;
    }

    /**
     * @return int|null
     */
    public function getHttpCode():?int
    {
        return $this->httpCode;
    }

    /**
     * @param int|null $httpCode
     *
     * @return $this
     */
    public function setHttpCode($httpCode):EventRequest
    {
        $this->httpCode = $httpCode;

        return $this;
    }
}
