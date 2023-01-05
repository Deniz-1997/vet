<?php

namespace App\Service;

use App\Packages\Normalizer\ApiExceptionNormalizer;
use App\Packages\Normalizer\EnumNormalizer;
use App\Packages\Normalizer\ExceptionNormalizer;
use App\Packages\Normalizer\PropertyNormalizer;
use DateTimeZone;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use GBProd\UuidNormalizer\UuidNormalizer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;

class SerializeService
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var AnnotationLoader
     */
    private $annotationLoader;

    /**
     * SerializeService constructor.
     * @param array $encoders
     * @param array $normalizers
     * @param RequestStack $requestStack
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(
        array $encoders = [],
        iterable $normalizers = [],
        RequestStack $requestStack,
        TranslatorInterface $translator
    ) {
        if (!is_array($normalizers)) {
            $normalizers = iterator_to_array($normalizers);
        }

        $propertyNormalizer = new PropertyNormalizer(
            new ClassMetadataFactory(
                new \Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader(
                    new AnnotationReader(
                        new DocParser()
                    )
                )
            )
        );

        $circularReferenceHandler = function ($object) {
            $data = [];

            if (method_exists($object, 'getId')) {
                $data['id'] = $object->getId();
            }
            if (method_exists($object, 'getCode')) {
                $data['code'] = $object->getCode();
            }

            return $data;
        };
        $propertyNormalizer->setCircularReferenceHandler($circularReferenceHandler);

        /**
         * Есть необходимость в исключении данных полей.
         * 1. При getItem после сериализации они останутся в связанных сущностях.
         * 2. При getList не сработает сериализация из-за closure type.
         */
        $propertyNormalizer->setIgnoredAttributes([
            '__initializer__',
            '__cloner__',
            '__isInitialized__',
            'deleted',
        ]);

        $this->serializer = new Serializer(
            array_merge($normalizers, [
                new ApiExceptionNormalizer(),
                new ExceptionNormalizer(),
                new DateTimeNormalizer('d.m.Y H:i:s'),
//                new DateTimeNormalizer('d.m.Y H:i:s', new \DateTimeZone('Europe/Moscow')),
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

