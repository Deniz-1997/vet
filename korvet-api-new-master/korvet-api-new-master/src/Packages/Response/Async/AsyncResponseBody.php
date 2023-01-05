<?php


namespace App\Packages\Response\Async;


use OpenApi\Annotations as SWG;

class AsyncResponseBody
{
    /**
     * @var string
     *
     * @SWG\Property(type="string", description="Идентификатор асинхронного запроса. По нему следует опрашивать результаты выполнения функции с помощью функции GET /async/result/{correlationId}/.")
     */
    public $correlationId;

    /**
     * @param string $correlationId
     * @return AsyncResponseBody
     */
    public function setCorrelationId(string $correlationId): AsyncResponseBody
    {
        $this->correlationId = $correlationId;
        return $this;
    }
}
