<?php

namespace App\Service;

use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;
use App\Packages\Normalizer\DefaultNormalizer;
use App\Packages\Normalizer\EnumDenormalizer;
use App\Packages\Normalizer\EnumNormalizer;

/**
 * Class DeserializeService
 *
 * @package App\Service
 */
class DeserializeService
{
    /**
     * @var Serializer
     */
    private Serializer $serializer;

    /**
     * DeserializeService constructor.
     *
     * @param array $encoders
     * @param array $normalizers
     * @param EntityManagerInterface $om
     * @param TranslatorInterface $translator
     * @param EventDispatcherInterface $eventDispatcher
     * @param PsrCachedReader $annotationReader
     */
    public function __construct(array $encoders = [], array $normalizers = [], EntityManagerInterface $om, TranslatorInterface $translator, EventDispatcherInterface $eventDispatcher, PsrCachedReader $annotationReader)
    {
        $defaultNormalizer = new DefaultNormalizer(null, null, null,
            new ReflectionExtractor(), $om, $translator, $eventDispatcher, $annotationReader);
        $defaultNormalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $this->serializer = new Serializer(
            array_merge($normalizers, [
                new ArrayDenormalizer(),
                new DateTimeNormalizer(),
                new EnumNormalizer($translator),
                new EnumDenormalizer($translator),
                $defaultNormalizer,
            ]),
            array_merge($encoders, [new JsonEncoder()])
        );
    }

    /**
     * @param string $data
     * @param string $type
     * @param string $format
     * @param array $context
     *
     * @return object
     */
    public function deserialize(
        string $data,
        string $type,
        string $format,
        array $context = []
    ): object
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
