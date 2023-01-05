<?php

namespace App\Packages\ArgumentResolvers;

use App\Exception\ApiException;
use App\Exception\ValidationExceptionCollection;
use App\Interfaces\Request as SupportRequest;
use Generator;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

final class RequestResolver implements ArgumentValueResolverInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     * @throws ReflectionException
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (null !== $argument->getType() && class_exists($argument->getType())) {
            $r = new ReflectionClass($argument->getType());
            if ($r->implementsInterface(SupportRequest::class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return Generator
     * @throws ValidationExceptionCollection
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $object = $this->serializer->deserialize(
            $request->getContent(),
            $argument->getType(),
            'json'
        );

        $errors = $this->validator->validate($object);

        if (0 < count($errors)) {
            $exceptions = [];

            /* @var $error ConstraintViolation */
            foreach ($errors as $error) {
                $exceptions[] = new ApiException(
                    $error->getMessage(),
                    'VALIDATION_ERROR',
                    $error->getPropertyPath()
                );
            }

            throw new ValidationExceptionCollection($exceptions);
        }

        yield $object;
    }
}
