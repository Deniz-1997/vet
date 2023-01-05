<?php


namespace App\Packages\Response\Async;


use App\Service\SerializeService;
use Nelmio\ApiDocBundle\Annotation\Model;
use phpseclib\Crypt\Base;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Enum\AsyncStatusEnum;
use App\Packages\Response\BaseResponse;

class AsyncResultResponse extends BaseResponse
{
    /** @var SerializeService */
    private $serializer;

    /**
     * @var boolean
     *
     * @SWG\Property(type="boolean", default="true", description="Признак успешного ответа сервиса")
     */
    public $status;

    /**
     * @var string
     *
     * @SWG\Property(type="object", ref=@Model(type=AsyncStatusEnum::class), description="Результат работы асинхронной функции")
     */
    public $asyncStatus;

    /**
     * @var mixed
     *
     * @SWG\Property(type="array", @SWG\Items(type="string"), description="Результат успешной работы сервиса, в случае status=true.")
     */
    public $response;

    /**
     * @param string $asyncStatus
     * @return AsyncResultResponse
     */
    public function setAsyncStatus(string $asyncStatus): AsyncResultResponse
    {
        $this->asyncStatus = $asyncStatus;
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param mixed $response
     * @return BaseResponse
     */
    public function setResponse($response): BaseResponse
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return array
     */
    public function prepareContent(): array
    {
        return [
            'status' => $this->status,
            'asyncStatus' => $this->asyncStatus,
            'response' => $this->response,
            'errors' => $this->getErrors(),
        ];
    }
}
