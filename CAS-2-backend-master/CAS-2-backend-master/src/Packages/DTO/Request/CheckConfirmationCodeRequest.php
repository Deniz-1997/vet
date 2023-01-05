<?php

namespace App\Packages\DTO\Request;

use OpenApi\Annotations as SWG;

/**
 * Class CheckConfirmationCodeRequest
 */
class CheckConfirmationCodeRequest
{
    /**
     * @var string
     *
     * @SWG\Property(
     *     type="string",
     *     description="Получатель"
     * )
     */
    public string $recipient;

    /**
     * @var mixed
     *
     * @SWG\Property(
     *     type="string",
     *     description="Полученный код"
     * )
     */
    public $code;
}
