<?php

namespace App\Packages\DTO\Request;

use OpenApi\Annotations as SWG;

/**
 * Class PasswordRecoveryConfirmationCodeRequest
 */
class PasswordRecoveryConfirmationCodeRequest
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

    /**
     * @var string
     *
     * @SWG\Property(
     *     type="string",
     *     description="Новый пароль"
     * )
     */
    public string $newPassword;

    /**
     * @var string
     *
     * @SWG\Property(
     *     type="string",
     *     description="Новый пароль (подтверждение)"
     * )
     */
    public string $newPasswordConfirm;
}
