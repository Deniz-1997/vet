<?php

namespace App\Service\EventDispatcher;

use App\Service\EventDispatcher\Event\DeserializeEvent;
use App\Service\HandlerException\Validation\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\ValidationService;
use App\Packages\Normalizer\AbstractObjectNormalizer;

/**
 * Class SubscriberSerializer
 */
class SubscriberSerializer implements EventSubscriberInterface
{
    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * SubscriberSerializer constructor.
     *
     * @param ValidationService $validationService
     */
    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            AbstractObjectNormalizer::EVENT_NAME_SERIALIZER_EXCEPTION => [
                ['onExceptionSerializer', -1],
            ]
        );
    }

    /**
     * @param DeserializeEvent $deserializeEvent
     *
     * @throws ValidationException
     */
    public function onExceptionSerializer(DeserializeEvent $deserializeEvent)
    {
        $errors = $this->validationService->validate($deserializeEvent->getEntity(), null, null, false);
        if (count($errors) > 0) {
            $deserializeEvent->setValidationException($errors);
        }
    }
}
