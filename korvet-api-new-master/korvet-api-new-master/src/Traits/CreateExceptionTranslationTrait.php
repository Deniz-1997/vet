<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;
use App\Exception\ApiException;

/**
 * Trait CreateExceptionTranslationTrait
 *
 * @property Translator $translator
 */
trait CreateExceptionTranslationTrait
{
    /**
     * Create ApiException
     *
     * @param string $message
     * @param array $response
     * @param integer $code
     * @param array $dataTranslator
     * @param string|null $relatedField
     *
     * @throws  ApiException
     */
    public function createException(string $message, array $response = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR, $dataTranslator = [], $relatedField = null)
    {
        if (isset($response['httpResponseCode'])) {
            $code = (int)$response['httpResponseCode'];
        }
        if (isset($response['relatedField'])) {
            $relatedField = $response['relatedField'];
        }
        $info = !empty($response['errors'][0]['message']) ? $response['errors'][0]['message'] : '';

        $messageException = $this->translator->trans($message, $dataTranslator, 'exception') . (!empty($info) ? ', info: ' . $info : '');

        throw new ApiException($messageException, 'Error_' . $code, $relatedField, $code);
    }
}
