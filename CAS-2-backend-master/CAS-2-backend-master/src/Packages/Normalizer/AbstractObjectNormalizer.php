<?php

namespace App\Packages\Normalizer;

use App\Packages\Annotation\SerializeNestedIgnore;
use App\Exception\DenormalizeCollectionException;
use App\Exception\DenormalizeException;
use App\Service\EventDispatcher\Event\DeserializeEvent;
use App\Service\HandlerException\ValidationAndNormalizationException;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\Proxy;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Traversable;
use function call_user_func;
use function gettype;
use function in_array;
use function is_array;
use function is_object;

/**
 * Class AbstractObjectNormalizer
 */
abstract class AbstractObjectNormalizer extends AbstractNormalizer
{

    const EVENT_NAME_SERIALIZER_EXCEPTION = 'onSerializerEventException';

    const ENABLE_MAX_DEPTH = 'enable_max_depth';

    const DEPTH_KEY_PATTERN = 'depth_%s::%s';

    const ALLOW_EXTRA_ATTRIBUTES = 'allow_extra_attributes';

    const DISABLE_TYPE_ENFORCEMENT = 'disable_type_enforcement';

    const EXCLUDE_FROM_CACHE_KEY = 'exclude_from_cache_key';

    private ?PropertyTypeExtractorInterface $propertyTypeExtractor;

    private array $attributesCache = [];

    private array $cache = [];

    /** @var TranslatorInterface */
    protected TranslatorInterface $translator;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $om;

    /** @var PsrCachedReader */
    private PsrCachedReader $annotationReader;

    /** @var EventDispatcherInterface */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * AbstractObjectNormalizer constructor.
     *
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     * @param EntityManagerInterface $om
     * @param TranslatorInterface $translator
     * @param EventDispatcherInterface $eventDispatcher
     * @param PsrCachedReader $annotationReader
     */
    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        EntityManagerInterface $om,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        PsrCachedReader $annotationReader,
        array $defaultContext = []
    )
    {
        parent::__construct($classMetadataFactory, $nameConverter, $defaultContext);

        $this->propertyTypeExtractor = $propertyTypeExtractor;
        $this->om = $om;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->annotationReader = $annotationReader;

        if ($this->circularReferenceHandler === null) {
            $this->circularReferenceHandler = function ($object) {
                return $object->getId();
            };
        }
        $this->defaultContext[self::EXCLUDE_FROM_CACHE_KEY] = array_merge($this->defaultContext[self::EXCLUDE_FROM_CACHE_KEY] ?? [], [self::CIRCULAR_REFERENCE_LIMIT_COUNTERS]);
    }

    /**
     * @param object|string $classOrObject
     * @param string $attribute
     * @param null $format
     * @param array $context
     * @return bool
     */
    protected function isAllowedAttribute($classOrObject, $attribute, $format = null, array $context = []): bool
    {
        if (isset($context['amqp']) && $context['amqp']) {
            foreach ($this->classMetadataFactory->getMetadataFor($classOrObject)->getAttributesMetadata() as $attributeMetadata) {
                if ($attributeMetadata->getName() == $attribute) {
                    $groups = $attributeMetadata->getGroups() ?? [];
                    if (in_array('amqp.expose', $groups)) {
                        return false;
                    }
                }
            }
        }

        return parent::isAllowedAttribute($classOrObject, $attribute, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return is_object($data) && !$data instanceof Traversable;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!isset($context['cache_key'])) {
            $context['cache_key'] = $this->getCacheKey($format, $context);
        }

        if ($this->isCircularReference($object, $context)) {
            return $this->handleCircularReference($object);
        }

        $data = [];
        $stack = [];
        $attributes = $this->getAttributes($object, $format, $context);
        $class = get_class($object);
        $attributesMetadata = $this->classMetadataFactory ? $this->classMetadataFactory->getMetadataFor($class)->getAttributesMetadata() : null;

        foreach ($attributes as $attribute) {
            if (null !== $attributesMetadata && $this->isMaxDepthReached($attributesMetadata,
                    $class, $attribute, $context)) {
                continue;
            }

            $attributeValue = $this->getAttributeValue($object, $attribute,
                $format, $context);

            if (isset($this->callbacks[$attribute])) {
                $attributeValue = call_user_func($this->callbacks[$attribute],
                    $attributeValue);
            }

            if (null !== $attributeValue && !is_scalar($attributeValue)) {
                $stack[$attribute] = $attributeValue;
            }

            $data = $this->updateData($data, $attribute, $attributeValue);
        }

        foreach ($stack as $attribute => $attributeValue) {
            if (!$this->serializer instanceof NormalizerInterface) {
                throw new LogicException(sprintf('error.cannot_notmalize_attribute.with_use_normalizer', $attribute));
            }

            $data = $this->updateData($data, $attribute,
                $this->serializer->normalize($attributeValue, $format,
                    $this->createChildContext($context, $attribute)));
        }

        return $data;
    }

    /**
     * Gets and caches attributes for the given object, format and context.
     *
     * @param object $object
     * @param string|null $format
     * @param array $context
     *
     * @return string[]
     */
    protected function getAttributes($object, $format = null, array $context): array
    {
        $class = get_class($object);
        $key = $class . '-' . $context['cache_key'];

        if (isset($this->attributesCache[$key])) {
            return $this->attributesCache[$key];
        }

        $allowedAttributes = $this->getAllowedAttributes($object, $context, true);

        if (false !== $allowedAttributes) {
            if ($context['cache_key']) {
                $this->attributesCache[$key] = $allowedAttributes;
            }

            return $allowedAttributes;
        }

        if (isset($context['attributes'])) {
            return $this->extractAttributes($object, $format, $context);
        }

        if (isset($this->attributesCache[$class])) {
            return $this->attributesCache[$class];
        }

        return $this->attributesCache[$class] = $this->extractAttributes($object,
            $format, $context);
    }

    /**
     * Extracts attributes to normalize from the class of the given object,
     * format and context.
     *
     * @param object $object
     * @param string|null $format
     * @param array $context
     *
     * @return string[]
     */
    abstract protected function extractAttributes(
        object $object,
        $format = null,
        array $context = []
    ): array;

    /**
     * Gets the attribute value.
     *
     * @param object $object
     * @param string $attribute
     * @param string|null $format
     * @param array $context
     *
     * @return mixed
     */
    abstract protected function getAttributeValue(
        object $object,
        string $attribute,
        $format = null,
        array $context = []
    );

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return isset($this->cache[$type]) ? $this->cache[$type] : $this->cache[$type] = class_exists($type);
    }

    /**
     * @param mixed $data
     * @param $type
     * @param null $format
     * @param array $context
     *
     * @return object
     * @throws EntityNotFoundException
     * @throws ExceptionInterface
     * @throws ReflectionException
     * @throws ValidationAndNormalizationException
     */
    public function denormalize($data, $type, $format = null, array $context = []): object
    {
        if (!isset($context['cache_key'])) {
            $context['cache_key'] = $this->getCacheKey($format, $context);
        }

        $allowedAttributes = $this->getAllowedAttributes($type, $context,
            true);
        $normalizedData = $this->prepareForDenormalization($data);
        $extraAttributes = [];

        $reflectionClass = new ReflectionClass($type);
        $object = $this->instantiateObject($normalizedData, $type, $context,
            $reflectionClass, $allowedAttributes, $format);

        $denormalizeErrors = [];

        foreach ($normalizedData as $attribute => $value) {
            try {
                if ($this->nameConverter) {
                    $attribute = $this->nameConverter->denormalize($attribute);
                }

                if ((false !== $allowedAttributes && !in_array($attribute,
                            $allowedAttributes, true)) || !$this->isAllowedAttribute($type,
                        $attribute, $format, $context)) {
                    if (isset($context[self::ALLOW_EXTRA_ATTRIBUTES]) && !$context[self::ALLOW_EXTRA_ATTRIBUTES]) {
                        $extraAttributes[] = $attribute;
                    }

                    continue;
                }

                $value = $this->validateAndDenormalize($type, $attribute, $value, $format, $context);
                try {
                    $this->setAttributeValue($object, $attribute, $value, $format, $context);
                } catch (InvalidArgumentException $e) {
                    $message = $e->getMessage();
                    if (preg_match('/\"(.*)\"+\,[\ ]{1,}\"(.*)\"+[\ ]{1,}given at property path +\"(.*)\"/i', str_replace('\\', '\\\\', $message), $match) && isset($match[1], $match[2], $match[3])) {
                        $key = 'error.wrong_type_of_property';
                        $params = [
                            'property' => $attribute,
                            'objectName' => $type,
                            'expectedType' => $match[1],
                            'givenType' => $match[2],
                            '{withNormalizeData}' => $match[3],
                        ];
                        $message = $this->translator->trans($key, $params, 'exception');
                    }
                    throw new NotNormalizableValueException($message, $e->getCode(), $e);
                }
            } catch (ValidationAndNormalizationException | EntityNotFoundException $e) {
                throw $e;
            } catch (Exception $e) {
                if ($e instanceof DenormalizeCollectionException) {
                    $denormalizeErrors[] = $e->getDenormalizeExceptions();
                } elseif ($e->getMessage()) {
                    $message = $e->getMessage();
                    if (preg_match('/The type of the (\w+) attribute in (\w+) must be one of (\w+) \((\w+) given\)/', $message, $matches)) {
                        dd($matches); // TODO проверить порядок ееременных в matches, оозможно перепутанны индексы переменных
                        $key = 'error.wrong_type_of_property';
                        $params = [
                            'property' => $matches[1],
                            'objectName' => $matches[2],
                            'expectedType' => $matches[3],
                            'givenType' => $matches[4],
                            '{withNormalizeData}' => mb_substr(json_encode($normalizedData), 0, 100, 'utf8') . '...',
                        ];
                        $message = $this->translator->trans($key, $params, 'exception', 'ru');
                    } elseif (preg_match('/\"(.*)\"+\,[\ ]{1,}\"(.*)\"+[\ ]{1,}given at property path +\"(.*)\"/i', str_replace('\\', '\\\\', $message), $match) && isset($match[1], $match[2], $match[3])) {
                        $key = 'error.wrong_type_of_property';
                        $params = [
                            'property' => $attribute,
                            'objectName' => $type,
                            'expectedType' => $match[1],
                            'givenType' => $match[2],
                            '{withNormalizeData}' => $match[3],
                        ];
                        $message = $this->translator->trans($key, $params, 'exception');
                    }
                    $denormalizeErrors[] = new DenormalizeException($attribute, $message);
                }
            }
        }

        if (!empty($extraAttributes)) {
            throw new ExtraAttributesException($extraAttributes);
        }

        if ([] !== $denormalizeErrors) {
            $event = $this->generateEvent($normalizedData, $object, $denormalizeErrors);

            throw new ValidationAndNormalizationException($event->getValidationException(), $event->getSerializerException());
        }

        return $object;
    }

    /**
     * @param array $normalizedData
     * @param string $entity
     * @param array $denormalizeErrors
     *
     * @return DeserializeEvent
     */
    protected function generateEvent($normalizedData, $entity, $denormalizeErrors)
    {
        $event = new DeserializeEvent($entity, $normalizedData, $denormalizeErrors);
        $this->eventDispatcher->dispatch(self::EVENT_NAME_SERIALIZER_EXCEPTION, $event);

        return $event;
    }

    /**
     * Sets attribute value.
     *
     * @param object $object
     * @param string $attribute
     * @param mixed $value
     * @param string|null $format
     * @param array $context
     */
    abstract protected function setAttributeValue(
        object $object,
        string $attribute,
        $value,
        $format = null,
        array $context = []
    );

    /**
     * Validates the submitted data and denormalizes it.
     *
     * @param string $currentClass
     * @param string $attribute
     * @param             $data
     * @param string|null $format
     * @param array $context
     *
     * @return mixed
     * @throws DenormalizeCollectionException
     * @throws ExceptionInterface
     */
    private function validateAndDenormalize(
        string $currentClass,
        string $attribute,
        $data,
        ?string $format,
        array $context
    )
    {
        try {
            if (null === $this->propertyTypeExtractor || null === $types = $this->propertyTypeExtractor->getTypes($currentClass, $attribute)) {
                return $data;
            }

            if (is_object($data)) {
                return $data;
            }

            $expectedTypes = [];
            foreach ($types as $type) {
                if (null === $data && $type->isNullable()) {
                    return;
                }

                if ($type->isCollection() && null !== ($collectionValueType = $type->getCollectionValueType()) && Type::BUILTIN_TYPE_OBJECT === $collectionValueType->getBuiltinType()) {
                    $builtinType = Type::BUILTIN_TYPE_OBJECT;
                    $class = $collectionValueType->getClassName() . '[]';

                    if (null !== $collectionKeyType = $type->getCollectionKeyType()) {
                        $context['key_type'] = $collectionKeyType;
                    }
                } else {
                    $builtinType = $type->getBuiltinType();
                    $class = $type->getClassName();
                }

                $expectedTypes[Type::BUILTIN_TYPE_OBJECT === $builtinType && $class ? $class : $builtinType] = true;

                $collectionClass = $class;
                if (substr($collectionClass, -2) == '[]') {
                    $collectionClass = substr($class, 0, strlen($class) - 2);
                }

                $isEntity = (
                    strpos($currentClass, 'App\Entity\\') !== false ||
                    (
                        strpos($currentClass, 'Webslon') !== false &&
                        strpos($currentClass, 'Entity') !== false &&
                        stripos($currentClass, 'dto') === false
                    )
                );

                if (Type::BUILTIN_TYPE_OBJECT === $builtinType) {
                    if ($isEntity && is_array($data) && isset($data[0]) && is_array($data[0]) && (array_key_exists('code', $data[0]) || array_key_exists('id', $data[0]))) {
                        $criteriaFetchingCollection = new Criteria();

                        $validData = false;
                        // default

                        $items = $delayedNewEntities = [];
                        foreach ($data as $k => $item) {
                            $isNewEntity = false;
                            if (isset($item['id'])) {
                                $findCollectionBy = 'id';
                                $searchValue = $item['id'];
                            } elseif (isset($item['code'])) {
                                $findCollectionBy = 'code';
                                $searchValue = $item['code'];
                            } else {
                                $isNewEntity = true;
                            }

                            if ($isNewEntity) {
                                unset($data[$k]);
                                $delayedNewEntities[] = $this->serializer->deserialize(json_encode($item), $collectionClass, 'json', $context);
                            } elseif ('id' === $findCollectionBy && !$searchValue) {
                                $items[] = $this->serializer->deserialize(json_encode($data), $collectionClass, 'json', $context);
                            } else {
                                $criteriaFetchingCollection->orWhere(
                                    $criteriaFetchingCollection->expr()->eq($findCollectionBy, $searchValue)
                                );
                                $validData = true;
                            }
                        }

                        if (!$validData) {
                            return array_merge($items, $delayedNewEntities);
                        }

                        $pa = PropertyAccess::createPropertyAccessor();
                        if ($collectionClass) {
                            $result = [];
                            $entitiesFromDatabase = $this->om->getRepository($collectionClass)->matching($criteriaFetchingCollection)->toArray();

                            foreach ($data as $item) {
                                $foundEntityFromDatabase = null;

                                //Если нужно найти по какому то полю (существует $findCollectionBy)
                                if (isset($findCollectionBy) && isset($item[$findCollectionBy])) {
                                    foreach ($entitiesFromDatabase as $entityFromDatabase) {
                                        $value = $pa->getValue($entityFromDatabase, $findCollectionBy);
                                        //Сущность была найдена в базе из общего Criteria - значит каскадные изменения примем на сущность из базы
                                        //А не на переданный JSON
                                        if ($value == $item[$findCollectionBy]) {
                                            $foundEntityFromDatabase = $entityFromDatabase;
                                        }
                                    }
                                }

                                //Удалим обозначение коллекции\массива объектов из класса, т.к. мы разбираем
                                //каждый объект измассива по-отдельности
                                if (mb_substr($class, mb_strlen($class) - 2, 2) === '[]') {
                                    $class = mb_substr($class, 0, mb_strlen($class) - 2);
                                }

                                //Если объект найден в бд - не обновляем его из JSON запроса
                                //Если не указано, что для данной связи можно использовать каскадное обновление
                                if ($foundEntityFromDatabase) {
                                    //Если указана аннотация SerializeNestedIgnore - не используем каскадное обновление
                                    //берем сущность из бд как она есть
                                    if ($this->fetchIgnoreNestedSerializeAnnotation($currentClass, $attribute)) {
                                        $result[] = $foundEntityFromDatabase;
                                    } else { //не указана - используем каскадное обновление (старая логика)
                                        $result[] = $this->serializer->deserialize(json_encode($item), $class, 'json', array_merge([$context, 'object_to_populate' => $foundEntityFromDatabase]));
                                    }
                                } else {
                                    //Применяем каскадные изменения на один из объектов в массиве
                                    //Т.к. это уже создание новой сущности, которой в бд нет по id, code
                                    $context['object_to_populate'] = $foundEntityFromDatabase;
                                    $result[] = $this->serializer->deserialize(json_encode($item), $class, 'json', $context);
                                }
                            }

                            if ($delayedNewEntities) {
                                $result = array_merge($result, $delayedNewEntities);
                            }

                            return $result;
                        }
                    }

                    if ($isEntity && is_array($data) && (array_key_exists('id', $data) || array_key_exists('code', $data))) {
                        try {
                            $findBy = isset($data['id']) ? 'id' : 'code';

                            $relatedObject = $this->om->getRepository($class)->findOneBy([$findBy => $data[$findBy]]);
                            if (!$relatedObject && ($findBy === 'id' && !$data[$findBy])) {
                                return $this->serializer->deserialize(json_encode($data), $class, 'json', $context);
                            }
                            if (!$relatedObject instanceof $class) {
                                $messageId = 'error.serialize_relation_entity';
                                $dataTranslator = [
                                    '{objectName}' => $this->translator->trans(substr(strrchr($class, "\\"), 1), [], 'classes'),
                                    '{property}' => $findBy,
                                    '{value}' => $data[$findBy],
                                ];
                                $message = $this->translator->trans($messageId, $dataTranslator, 'exception');
                                throw new EntityNotFoundException($message);
                            }
                            $context['object_to_populate'] = $relatedObject;

                            return $this->serializer->deserialize(json_encode($data), get_class($relatedObject), 'json', $context);
                        } catch (MappingException $mappingException) {
                            //Is object has id or code field, but is not entity - skip for normally object normalization
                        }
                    }

                    if (!$this->serializer instanceof DenormalizerInterface) {
                        throw new LogicException(sprintf('Cannot denormalize attribute "%s" for class "%s" because injected serializer is not a denormalizer', $attribute, $class));
                    }

                    $childContext = $this->createChildContext($context, $attribute);
                    if ($this->serializer->supportsDenormalization($data, $class, $format, $childContext)) {
                        return $this->serializer->denormalize($data, $class, $format, $childContext);
                    }
                }

                // JSON only has a Number type corresponding to both int and float PHP types.
                // PHP's json_encode, JavaScript's JSON.stringify, Go's json.Marshal as well as most other JSON encoders convert
                // floating-point numbers like 12.0 to 12 (the decimal part is dropped when possible).
                // PHP's json_decode automatically converts Numbers without a decimal part to integers.
                // To circumvent this behavior, integers are converted to floats when denormalizing JSON based formats and when
                // a float is expected.
                if (Type::BUILTIN_TYPE_FLOAT === $builtinType && is_int($data) && false !== strpos($format,
                        JsonEncoder::FORMAT)) {
                    return (float)$data;
                }

                if (call_user_func('is_' . $builtinType, $data)) {
                    return $data;
                }
            }

            if (!empty($context[self::DISABLE_TYPE_ENFORCEMENT])) {
                return $data;
            }

            $messageId = 'error.wrong_type_of_property';
            $dataTranslator = [
                '{objectName}' => $this->translator->trans(substr(strrchr($currentClass, "\\"), 1), [], 'classes'),
                '{property}' => $attribute,
                '{expectedType}' => implode('", "', array_keys($expectedTypes)),
                '{givenType}' => gettype($data),
                '{withNormalizeData}' => mb_substr(json_encode($data), 0, 100, 'utf8') . '...',
            ];
            $message = $this->translator->trans($messageId, $dataTranslator, 'exception');

            throw new NotNormalizableValueException($message);
        } catch (Exception $e) {
            if ($e instanceof ValidationAndNormalizationException || $e instanceof EntityNotFoundException) {
                throw $e;
            }
            throw new DenormalizeCollectionException([new DenormalizeException($attribute, $e->getMessage())], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param string $class
     * @param string $attribute
     * @return SerializeNested|null
     * @throws ReflectionException
     */
    private function fetchIgnoreNestedSerializeAnnotation(string $class, string $attribute): ?SerializeNestedIgnore
    {
        $cacheKey = 'serialize_nested_annotation_cache_' . $class . '_' . $attribute;

        if (is_subclass_of($class, Proxy::class)) {
            $refClass = new ReflectionClass($class);
            $class = $refClass->getParentClass()->getName();
        }

        if (!isset($this->cache[$cacheKey])) {
            $annotation = $this->annotationReader->getPropertyAnnotation(new ReflectionProperty($class, $attribute), SerializeNestedIgnore::class);
            $this->cache[$cacheKey] = $annotation;
        }

        return $this->cache[$cacheKey];
    }

    /**
     * @param array $data
     * @param string $attribute
     * @param mixed $attributeValue
     *
     * @return array
     */
    private function updateData(array $data, string $attribute, $attributeValue): array
    {
        if ($this->nameConverter) {
            $attribute = $this->nameConverter->normalize($attribute);
        }

        $data[$attribute] = $attributeValue;

        return $data;
    }

    /**3
     * @param array $attributesMetadata
     * @param string $class
     * @param string $attribute
     * @param array $context
     *
     * @return bool
     */
    private function isMaxDepthReached(array $attributesMetadata, string $class, string $attribute, array &$context): bool
    {
        if (!isset($context[static::ENABLE_MAX_DEPTH]) || !isset($attributesMetadata[$attribute]) ||
            null === $maxDepth = $attributesMetadata[$attribute]->getMaxDepth()
        ) {
            return false;
        }

        $key = sprintf(static::DEPTH_KEY_PATTERN, $class, $attribute);
        if (!isset($context[$key])) {
            $context[$key] = 1;

            return false;
        }

        if ($context[$key] === $maxDepth) {
            return true;
        }

        ++$context[$key];

        return false;
    }

    /**
     * @param string|null $format
     * @param array $context
     * @return bool|string
     */
    private function getCacheKey(?string $format, array $context)
    {
        foreach ($context[self::EXCLUDE_FROM_CACHE_KEY] ?? $this->defaultContext[self::EXCLUDE_FROM_CACHE_KEY] as $key) {
            unset($context[$key]);
        }
        unset($context[self::EXCLUDE_FROM_CACHE_KEY]);
        unset($context['cache_key']); // avoid artificially different keys

        try {
            return md5($format.serialize([
                    'context' => $context,
                    'ignored' => $this->ignoredAttributes,
                    'camelized' => $this->camelizedAttributes,
                ]));
        } catch (\Exception $exception) {
            // The context cannot be serialized, skip the cache
            return false;
        } catch (\Throwable $e) {
            return false; // <-- THIS
        }
    }
}
