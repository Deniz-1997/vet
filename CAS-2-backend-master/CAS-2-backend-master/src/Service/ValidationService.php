<?php

namespace App\Service;


use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    const GROUP_DEFAULT = 'Default';

    const GROUP_CREATE = 'createEntity';

    const GROUP_UPDATE = 'updateEntity';

    const GROUP_UPDATE_PUT = 'updateEntityPut';

    const GROUP_UPDATE_PUT_REPLACE = 'replaceEntityPut';

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var BaseResponse
     */
    private BaseResponse $response;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;
    /**
     * @var TelegramBotService
     */
    private TelegramBotService $telegramBotService;

    /**
     * ValidationService constructor.
     *
     * @param ValidatorInterface $validator
     * @param BaseResponse $response
     * @param TranslatorInterface $translator
     */
    public function __construct(ValidatorInterface $validator, BaseResponse $response, TranslatorInterface $translator, TelegramBotService $telegramBotService)
    {
        $this->validator = $validator;
        $this->response = $response;
        $this->translator = $translator;
        $this->telegramBotService = $telegramBotService;
    }

    /**
     * @param mixed $entity - Default object entity
     * @param null $constraints
     * @param array|string|null $groups
     * @param boolean $isThrow
     *
     * @return ConstraintViolationListInterface
     * @throws ValidationException
     */
    public function validate($entity, $constraints = null, $groups = null, $isThrow = true): ConstraintViolationListInterface
    {
        /** @var ConstraintViolationListInterface $errors */
        $errors = $this->validator->validate($entity, $constraints, $groups);

        if (\count($errors) > 0 && $isThrow) {
            $msg = "";
            foreach ($errors as $error) {
                $msg .= $error->getMessage();
            }

            $this->telegramBotService
                ->setMarkDown(false)
                ->setChannel(getenv('TELEGRAM_EXCEPTION_CHAT_ID'))
                ->send_message($msg);

            throw new ValidationException($errors);
        }

        return $errors;
    }

    /**
     * @param mixed $value - Default object entity
     * @param null $constraints
     * @param null $groups
     * @param string $domain - domain param for translator
     *
     * @return BaseResponse
     */
    public function validateAndGetResponse($value, $constraints = null, $groups = null, $domain = 'messages'): BaseResponse
    {
        $errors = $this->validator->validate($value, $constraints, $groups, false);

        if (count($errors)) {
            $this->response
                ->setErrors($this->mapError($errors))
                ->setHttpResponseCode(Response::HTTP_BAD_REQUEST)
                ->statusError();
        }

        return $this->response;
    }

    /**
     * @param array ...$errors
     *
     * @return array
     */
    public function mapError(...$errors): array
    {
        $map = $errors; // default
        foreach ($errors as $violations) {
            if (is_array($violations)) {
                foreach ($violations as $field => $message) {
                    $map[$field] = $this->translator->trans($message, ['field' => $field]);
                }
            } elseif ($violations instanceof ConstraintViolationList) {
                foreach ($violations as $violation) {
                    $map[$violation->getPropertyPath()] = $this->translator->trans($violation->getMessage(), ['field' => $violation->getPropertyPath()]);
                }
            }
        }

        return $map;
    }

    /**
     * @param array $message
     * @param array $value
     *
     * @return ApiException
     */
    protected function createException(array $message, $value = []): ApiException
    {
        return new ApiException(implode(', ', $message), 'Error_' . Response::HTTP_BAD_REQUEST, $value, Response::HTTP_BAD_REQUEST);
    }
}
