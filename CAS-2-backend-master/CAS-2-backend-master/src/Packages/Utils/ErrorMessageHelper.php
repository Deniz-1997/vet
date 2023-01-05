<?php

namespace App\Packages\Utils;

use App\Exception\DenormalizeException;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Service\HandlerException\Validation\ValidationException;
use App\Service\HandlerException\ValidationAndNormalizationException;

/**
 * Class ErrorMessageHelper
 */
class ErrorMessageHelper
{
    /**
     * @param Error|Exception $ex
     *
     * @return string
     */
    public static function getMessageError($ex): string
    {
        $messages = [];
        if ($ex->getMessage()) {
            $messages[] = $ex->getMessage();
        } else{
            if($ex instanceof ValidationException){
                foreach ($ex->getConstraints() ? : [] as $constraint) {
                    $messages[] = sprintf('[%s] %s', $constraint->getPropertyPath(), $constraint->getMessage());
                }
            } elseif ($ex instanceof ValidationAndNormalizationException){
                foreach ($ex->getValidationError() ?: [] as $violation) {
                    $messages[] = sprintf('[%s] %s', $violation->getPropertyPath(), $violation->getMessage());
                }
                $normalizationErrors = array_map(function ($error) {
                    if (is_array($error) && isset($error[0]) && $error[0] instanceof DenormalizeException) {
                        return $error[0]->getMessage();
                    }
                    if (is_scalar($error)) {
                        return $error;
                    }

                    return gettype($error);
                }, $ex->getDenormalizationError() ?: []);
                $messages[] = implode(', ', $normalizationErrors);
            }
        }

        return implode(',', $messages);
    }

    /**
     * @param Error|Exception $exception
     * @return bool
     */
    public static function isTimeout($exception):bool
    {
        return $exception->getMessage() === 'Gateway response doesn\'t contain a valid json.' ||
            in_array($exception->getCode(), [Response::HTTP_BAD_GATEWAY, Response::HTTP_GATEWAY_TIMEOUT], true) ||
            (stripos($exception->getMessage(), 'cURL error 28') !== false) ||
            (stripos($exception->getMessage(), 'No connection to server') !== false);
    }

    /**
     * @param array $errors
     * @return array
     */
    public static function errorsToString(array $errors): array
    {
        $items = [];
        foreach ($errors as $error) {
            if (is_array($error)) {
                $items[] = $error['message'] ;
            } else {
                $items[] = $error ;
            }
        }

        return $items;
    }
}
