<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\Response;
use App\Packages\Response\BaseItemChildResponse;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class EntityHistoryResponse
 */
class EntityHistoryResponse
{
    /**
     * @var boolean
     * @SWG\Property(type="boolean")
     */
    private bool $status = true;

    /**
     * @var integer
     * @SWG\Property(type="integer", example=200)
     */
    private int $httpResponseCode = Response::HTTP_OK;

    /**
     * @var array|null
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=ApiException::class)))
     */
    private ?array $errors;

    /**
     * @var BaseItemChildResponse
     * @SWG\Property(type="object", ref=@Model(type=HistoryList::class))
     */
    private BaseItemChildResponse $response;

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpResponseCode;
    }

    /**
     * @param int $httpResponseCode
     */
    public function setHttpResponseCode(int $httpResponseCode): void
    {
        $this->httpResponseCode = $httpResponseCode;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array|null $errors
     */
    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return BaseItemChildResponse
     */
    public function getResponse(): BaseItemChildResponse
    {
        return $this->response;
    }

    /**
     * @param BaseItemChildResponse $response
     */
    public function setResponse(BaseItemChildResponse $response): void
    {
        $this->response = $response;
    }
}
