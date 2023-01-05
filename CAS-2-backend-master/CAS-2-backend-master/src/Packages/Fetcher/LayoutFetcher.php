<?php


namespace App\Packages\Fetcher;

use App\Packages\DBAL\Types\Enum;
use App\Service\SerializationContextFetcher;
use Doctrine\Common\Annotations\PsrCachedReader;
use OpenApi\Annotations\Property;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Serializer\Mapping\ClassMetadataInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

/**
 * Class LayoutFetcher
 */
class LayoutFetcher
{
    /** @var PropertyInfoExtractorInterface */
    private $propertyInfo;

    /** @var PsrCachedReader */
    private $annotationReader;

    /** @var SerializationContextFetcher */
    private $serializationContextFetcher;

    /** @var ClassMetadataFactory */
    private $classMetadataFactory;

    /** @var string[] */
    private $alreadyParsed = [];

    /** @var ClassMetadataInterface[] */
    private $classMetadataCache = [];

    /**
     * TestCommand constructor.
     *
     * @param PropertyInfoExtractorInterface $propertyInfo
     * @param PsrCachedReader $annotationReader
     * @param SerializationContextFetcher $serializationContextFetcher
     * @param ClassMetadataFactoryInterface $classMetadataFactory
     */
    public function __construct(
        PropertyInfoExtractorInterface $propertyInfo,
        PsrCachedReader $annotationReader,
        SerializationContextFetcher $serializationContextFetcher,
        ClassMetadataFactoryInterface $classMetadataFactory
    ) {
        $this->propertyInfo = $propertyInfo;
        $this->annotationReader = $annotationReader;
        $this->serializationContextFetcher = $serializationContextFetcher;
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * @param string $entityClass
     * @param bool   $access
     * @param string $prevKey
     * @return array
     */
    public function getLayout(string $entityClass, bool $access, string $prevKey = '') : array
    {
        $layout = [];

        foreach ($this->propertyInfo->getProperties($entityClass) ?? [] as $property) {
            if (in_array($property, ['setId', 'setExternalId', 'setCode'])) {
                continue;
            }

            if (!$types = $this->propertyInfo->getTypes($entityClass, $property)) {
                continue;
            }

            $type = $types[0];
            if ($type->isCollection()) {
                continue;
            }

            if ('object' == $type->getBuiltinType() && $type->getClassName()) {
                if (isset($this->alreadyParsed[$entityClass.'#'.$property])) {
                    continue;
                }

                $className = $type->getClassName();
                if (is_subclass_of($className, Enum::class)) {
                    $this->alreadyParsed[$entityClass.'#'.$property] = true;
                    $layout[] = $this->getFieldInfo($entityClass, $property, $property, $access);
                } else {
                    $this->alreadyParsed[$entityClass.'#'.$property] = true;
                    try {
                        $layout = array_merge($layout, $this->getLayout($className, $access, $prevKey ? $prevKey.'.'.$property : $property));
                    } catch (\ReflectionException $reflectionException) {
                        continue;
                    }
                }
            } else {
                try {
                    $layout[] = $this->getFieldInfo($entityClass, $property, $prevKey ? $prevKey.'.'.$property : $property, $access);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $layout;
    }

    /**
     * @param string $entityClass
     * @param string $property
     * @param string $path
     * @param bool   $access
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    private function getFieldInfo($entityClass, $property, $path, bool $access)
    {
        $info = [];

        $reflectionProperty = new \ReflectionProperty($entityClass, $property);
        /** @var Property|null $propertyAnnotation */
        $propertyAnnotation = $this->annotationReader->getPropertyAnnotation($reflectionProperty, Property::class);
        $info['title'] = $propertyAnnotation->title ?? '';
        $info['description'] = $propertyAnnotation->description ?? '';
        $info['type'] = $propertyAnnotation->type ?? '';
        $info['format'] = $propertyAnnotation->format ?? '';
        $info['path'] = $path;

        if ($access) {
            $patchGroups = $this->serializationContextFetcher->getSerializationGroups('patch', $entityClass);
            $putGroups = $this->serializationContextFetcher->getSerializationGroups('put', $entityClass);
            $postGroups = $this->serializationContextFetcher->getSerializationGroups('post', $entityClass);
            $detailGroups = $this->serializationContextFetcher->getSerializationGroups('detail', $entityClass);
            $listGroups = $this->serializationContextFetcher->getSerializationGroups('list', $entityClass);

            $info['visible'] = $this->hasAccess(array_merge($detailGroups, $listGroups), $entityClass, $property);
            $info['disabled'] = !$this->hasAccess(array_merge($patchGroups, $postGroups, $putGroups), $entityClass, $property);
        }

        return $info;
    }

    /**
     * @param string[] $groups
     * @param string   $entityClass
     * @param string   $property
     *
     * @return bool
     */
    private function hasAccess(array $groups, string $entityClass, string $property) : bool
    {
        $attributeMetadata = null;
        foreach ($this->getClassMetadata($entityClass)->getAttributesMetadata() as $metadata) {
            if ($metadata->getName() == $property) {
                $attributeMetadata = $metadata;
                break;
            }
        }

        if (!$attributeMetadata) {
            return true;
        }

        $metadataGroups = $attributeMetadata->getGroups();
        $this->removeNotPermissionGroups($metadataGroups);
        $this->removeNotPermissionGroups($groups);

        $intersect = array_intersect($groups, $metadataGroups);
        $hasRoleRoot = array_search('permission.root', $groups) !== false;

        $this->removeNotPermissionGroups($intersect);

        return $hasRoleRoot || $intersect || !$metadataGroups;
    }

    private function removeNotPermissionGroups(array &$groups)
    {
        foreach ($groups as $k => $v) {
            if ('permission.' !== substr($v, 0, 11)) {
                unset($groups[$k]);
            }
        }
    }

    /**
     * @param string $entityClass
     * @return ClassMetadataInterface
     */
    private function getClassMetadata(string $entityClass)
    {
        if (!isset($this->classMetadataCache[$entityClass])) {
            $this->classMetadataCache[$entityClass] = $this->classMetadataFactory->getMetadataFor($entityClass);
        }

        return $this->classMetadataCache[$entityClass];
    }
}
