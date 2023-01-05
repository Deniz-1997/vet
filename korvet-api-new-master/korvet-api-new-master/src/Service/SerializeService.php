<?php

namespace App\Service;

use App\Packages\Normalizer\ApiExceptionNormalizer;
use App\Packages\Normalizer\EnumNormalizer;
use App\Packages\Normalizer\ExceptionNormalizer;
use App\Packages\Normalizer\PropertyNormalizer;
use App\Packages\Utils\CircularReferenceHandler;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use GBProd\UuidNormalizer\UuidNormalizer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;


class SerializeService
{
    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * @var AnnotationLoader
     */
    private AnnotationLoader $annotationLoader;

    /**
     * SerializeService constructor.
     * @param array $encoders
     * @param iterable $normalizers
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        array $encoders = [],
        iterable $normalizers = []
    ) {
        if (!is_array($normalizers)) {
            $normalizers = iterator_to_array($normalizers);
        }
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                $data = [];
                if (method_exists($object, 'getId')) {
                    return $object->getId();
                }
                if (method_exists($object, 'getCode')) {
                    return $object->getCode();
                }
                return $data;
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                '__initializer__',
                '__cloner__',
                '__isInitialized__',
                'deleted',
            ],
        ];
        $defaultContextDateTime = [DateTimeNormalizer::FORMAT_KEY => 'd.m.Y H:i:s'];

        $propertyNormalizer = new PropertyNormalizer(
            new ClassMetadataFactory(
                new \Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader(
                    new AnnotationReader(
                        new DocParser()
                    )
                )
            ), null,
            null,
            null,
            null,
            $defaultContext
        );
        $this->serializer = new Serializer(
            array_merge($normalizers, [
                new ApiExceptionNormalizer(),
                new ExceptionNormalizer(),
                new DateTimeNormalizer($defaultContextDateTime),
                new EnumNormalizer($translator),
                new UuidNormalizer(),
                $propertyNormalizer,
            ]),
            array_merge($encoders, [new JsonEncoder()])
        );
    }

    /**
     * @param $data
     * @param string $format
     * @param array $context
     * @return bool|float|int|string
     */
    public function serialize(
        $data,
        string $format,
        array $context = []
    ) {
        return $this->serializer->serialize($data, $format, $context);
    }
}
