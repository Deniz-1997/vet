<?php

namespace App\Packages\Describer;

use Doctrine\Common\Annotations\Reader;
use EXSyst\Component\Swagger\Schema;
use JMS\Serializer\Metadata\PropertyMetadata;
use Nelmio\ApiDocBundle\Describer\ModelRegistryAwareInterface;
use Nelmio\ApiDocBundle\Describer\ModelRegistryAwareTrait;
use Nelmio\ApiDocBundle\Model\Model;
use Nelmio\ApiDocBundle\ModelDescriber\Annotations\AnnotationsReader;
use Nelmio\ApiDocBundle\ModelDescriber\ModelDescriberInterface;
use OpenApi\Annotations\Property;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\Utils\PropertyAccessor;
use App\Packages\Response\BaseItemChildResponse;
use App\Packages\Response\BaseListItemResponse;
use App\Packages\Response\BaseListItemsContainResponse;

/**
 * Uses the JMS metadata factory to extract input/output model information.
 */
class ModelDescriber implements ModelDescriberInterface, ModelRegistryAwareInterface
{
    use ModelRegistryAwareTrait;

    private PropertyInfoExtractorInterface $propertyInfo;

    private Reader $doctrineReader;

    private $swaggerDefinitionAnnotationReader;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * ModelDescriber constructor.
     *
     * @param PropertyInfoExtractorInterface $propertyInfo
     * @param Reader                         $reader
     * @param TranslatorInterface            $translator
     */
    public function __construct(
        PropertyInfoExtractorInterface $propertyInfo,
        Reader $reader,
        TranslatorInterface $translator
    )
    {
        $this->propertyInfo = $propertyInfo;
        $this->doctrineReader = $reader;
        $this->translator = $translator;
    }

    /**
     * @param \App\Model\Model $model
     * @param Schema                                $schema
     *
     * @throws \ReflectionException
     */
    public function describe($model, $schema)
    {
        $schema->setType('object');

        $properties = $schema->getProperties();
        $class = $model->getType()->getClassName();

        $context = [];

        $annotationsReader = new AnnotationsReader($this->doctrineReader, $this->modelRegistry);
        $annotationsReader->updateDefinition(new \ReflectionClass($class), $schema);
        $swaggerAnnotationReader = new AnnotationsReader($this->doctrineReader);

        $propertyInfoProperties = $this->propertyInfo->getProperties($class, $context);
        if (null === $propertyInfoProperties) {
            return;
        }

        foreach ($propertyInfoProperties as $propertyName) {
            // read property options from Swagger Property annotation if it exists
            if (property_exists($class, $propertyName)) {
                $reflectionProperty = new \ReflectionProperty($class, $propertyName);

                $swgProperty = null;
                $enumAnnotation = $this->doctrineReader->getClassAnnotation(new \ReflectionClass($model->getType()->getClassName()), EnumAnnotation::class);
                if (!$enumAnnotation && !$swgProperty = $this->doctrineReader->getPropertyAnnotation($reflectionProperty, Property::class)) {
                    continue;
                }

                $property = $properties->get($annotationsReader->getPropertyName($reflectionProperty, $propertyName));
                if ($enumAnnotation instanceof EnumAnnotation) {
                    if ($propertyName === 'choices') {
                        continue;
                    }
                    if ($propertyName === 'code') {
                        $property->setType('object');
                        $property->setEnum(array_keys($class::choices()));
                    }
                    if ($propertyName === 'title') {
                        $enums = [];
                        $description = [];
                        foreach ($class::choices() as $key => $v) {
                            $translation = $this->translator->trans($v, [], 'enum');
                            $enums[$key] = $translation;
                            $description[] = $key . ' - ' . $translation;
                        }
                        $property->setEnum($enums);
                        $property->setType('object');
                        /** @var Property $swgProperty */
                        $customDesription = $schema->getDescription() ? $schema->getDescription() : '';
                        $enumValueToString = 'Enum values: ' . implode(', ', $description);
                        $schema->setDescription($customDesription ? $customDesription . ', ' . $enumValueToString : $enumValueToString);
                    }

                    $swaggerAnnotationReader->updateWithSwaggerPropertyAnnotation($reflectionProperty, $property);
                    continue;
                }
                $swaggerAnnotationReader->updateWithSwaggerPropertyAnnotation($reflectionProperty, $property);
            } else {
                continue;
            }

            // If type manually defined
            if (null !== $property->getType() || null !== $property->getRef()) {
                continue;
            }

            $types = $this->propertyInfo->getTypes($class, $propertyName);
            if (null === $types || 0 === count($types)) {
                continue;
            }

            if (count($types) > 1) {
                throw new \LogicException(sprintf('Property %s::$%s defines more than one type.', $class, $propertyName));
            }

            $type = $types[0];
            if (Type::BUILTIN_TYPE_ARRAY === $type->getBuiltinType()) {
                $type = $type->getCollectionValueType();

                if ($type->getClassName() == BaseListItemResponse::class) {
                    $type = new Type(
                        Type::BUILTIN_TYPE_OBJECT,
                        false,
                        $model->getContextFor('pseudoType')
                    );
                }

                if (null === $type) {
                    throw new \LogicException(sprintf('Property "%s:%s" is an array, but no indication of the array elements are made. Use e.g. string[] for an array of string.', $class, $propertyName));
                }

                $property->setType('array');
                $property = $property->getItems();
            }

            if (Type::BUILTIN_TYPE_STRING === $type->getBuiltinType()) {
                $property->setType('string');
            } elseif (Type::BUILTIN_TYPE_BOOL === $type->getBuiltinType()) {
                $property->setType('boolean');
            } elseif (Type::BUILTIN_TYPE_INT === $type->getBuiltinType()) {
                $property->setType('integer');
            } elseif (Type::BUILTIN_TYPE_FLOAT === $type->getBuiltinType()) {
                $property->setType('number');
                $property->setFormat('float');
            } elseif (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()) {
                if (in_array($type->getClassName(), ['DateTime', 'DateTimeImmutable'])) {
                    $property->setType('string');
                    $property->setFormat('date-time');
                } else {
                    if (in_array($type->getClassName(), [BaseListItemResponse::class, BaseItemChildResponse::class])) {
                        $type = new Type(
                            Type::BUILTIN_TYPE_OBJECT,
                            false,
                            $model->getContextFor('pseudoType')
                        );

                        $subModel = new Model($type, $model->getGroups());
                    } elseif ($type->getClassName() == BaseListItemsContainResponse::class) {
                        $subModel = new \App\Model\Model($type, $model->getGroups(), $model->getContext());

                        $names = PropertyAccessor::getValueForce($this->modelRegistry, 'names');
                        $names[$subModel->getHash()] = $model->getContextFor('entityName') . 'List';
                        PropertyAccessor::setValueForce($this->modelRegistry, 'names', $names);

                        $models = PropertyAccessor::getValueForce($this->modelRegistry, 'models');
                        $models[$subModel->getHash()] = $subModel;
                        PropertyAccessor::setValueForce($this->modelRegistry, 'models', $models);

                        $unregistered = PropertyAccessor::getValueForce($this->modelRegistry, 'unregistered');
                        $unregistered[] = $subModel->getHash();
                        PropertyAccessor::setValueForce($this->modelRegistry, 'unregistered', $unregistered);
                    } else {
                        $subModel = new Model($type);
                    }

                    $property->setRef(
                        $this->modelRegistry->register($subModel)
                    );
                }
            } else {
                throw new \Exception(sprintf('Unknown type: %s', $type->getBuiltinType()));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($model): bool
    {
        return $model instanceof \App\Model\Model || $this->doctrineReader->getClassAnnotation(new \ReflectionClass($model->getType()->getClassName()), EnumAnnotation::class) instanceof EnumAnnotation;
    }

    private function getNestedTypeInArray(PropertyMetadata $item)
    {
        if ('array' !== $item->type['name'] && 'ArrayCollection' !== $item->type['name']) {
            return;
        }

        if (isset($item->type['params'][1]['name'])) {
            return $item->type['params'][1]['name'];
        }

        if (isset($item->type['params'][0]['name'])) {
            return $item->type['params'][0]['name'];
        }
    }
}
