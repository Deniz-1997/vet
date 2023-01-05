<?php

namespace App\Exception;

use App\Model\Env;
use Exception;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as SWG;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ApiException
 */
class ApiException extends Exception
{
    /**
     * @var string
     * @SWG\Property(type="string", description="Тестовый код ошибки, например UNKNOWN_ERROR для удобной обработки на подключающихся клиентах")
     */
    protected $stringCode;

    /**
     * @var string|null
     * @SWG\Property(type="string", description="Поле объекта, связанное с ошибкой, например, для валидации форм")
     */
    protected $relatedField;

    /**
     * @var string
     * @SWG\Property(type="string", description="Текст ошибки")
     */
    protected $message;
    /**
     * @var string
     */
    protected $env;
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var string
     */
    protected $domainTranslator = 'exception';

    /**
     * @var array
     */
    protected $translationParameters;

    /**
     * @var string
     * @SWG\Property(type="string", description="Тип ошибки в виде имени класса exception")
     */
    protected $type;
    /**
     * @var string
     */
    protected $errorTrace;

    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param string $stringCode
     * @param string|null $relatedField
     * @param int $code
     * @param array $translationParameters
     */
    public function __construct(
        string $message = 'Unknown error',
        string $stringCode = 'UNKNOWN_ERROR',
        string $relatedField = null,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $translationParameters = []
    ) {
        $this->stringCode = $stringCode;
        $this->relatedField = $relatedField;
        $this->message = $message;
        $this->translationParameters = $translationParameters;

        parent::__construct($this->message, $code);
    }

    /**
     * @return string
     */
    public function getStringCode(): string
    {
        return $this->stringCode;
    }

    /**
     * @return null|string
     */
    public function getRelatedField(): ?string
    {
        return $this->relatedField;
    }

    /**
     * @return array
     */
    public function getTranslationParameters(): array
    {
        return $this->translationParameters;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getType():string
    {
        return $this->type ?? (new ReflectionClass($this))->getName();
    }

    /**
     * @param string $type
     *
     * @return ApiException
     */
    public function setType(string $type):ApiException
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string|null $traceString
     *
     * @return $this
     */
    public function setErrorTrace(?string $traceString):ApiException
    {
        $this->errorTrace = $traceString;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorTrace():?string
    {
        return Env::getenv('APP_ENV') !== 'prod' ? $this->errorTrace : null;
    }
}
