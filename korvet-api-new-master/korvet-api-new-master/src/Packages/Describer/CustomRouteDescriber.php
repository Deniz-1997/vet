<?php

namespace App\Packages\Describer;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Response;
use EXSyst\Component\Swagger\Schema;
use EXSyst\Component\Swagger\Swagger;
use Nelmio\ApiDocBundle\Describer\ModelRegistryAwareInterface;
use Nelmio\ApiDocBundle\Model\Model;
use Nelmio\ApiDocBundle\Model\ModelRegistry;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\OpenApi;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Routing\Route;
use App\Packages\Annotation\Operation;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\DynamicEntityClassControllerInterface;
use App\Controller\EnumController;
use App\Service\CRUD\EnumService;
use App\Packages\Utils\PropertyAccessor;
use App\Packages\Response\BaseItemResponse;
use App\Packages\Response\BaseListResponse;
use function defined;
use function get_class;

class CustomRouteDescriber implements RouteDescriberInterface, ModelRegistryAwareInterface
{
    use RouteDescriberTrait;

    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var AnnotationReader */
    private $annotationReader;

    /** @var ModelRegistry */
    private ModelRegistry $modelRegistry;

    /**
     * CustomRouteDescriber constructor.
     *
     * @param ContainerInterface $container
     * @param Reader $annotationReader
     */
    public function __construct(ContainerInterface $container, Reader $annotationReader)
    {
        $this->container = $container;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ModelRegistry $modelRegistry
     */
    public function setModelRegistry(ModelRegistry $modelRegistry)
    {
        $this->modelRegistry = $modelRegistry;
    }


    /**
     * @param OpenApi $api
     * @param Route $route
     * @param ReflectionMethod $reflectionMethod
     * @throws ReflectionException
     */
    public function describe(OpenApi $api, Route $route, ReflectionMethod $reflectionMethod)
    {
        $route->compile();

        if (strpos($route->getDefault('_controller'), '::') !== false) {
            list($controller, $action) = explode('::', $route->getDefault('_controller'));
        } elseif (strpos($route->getDefault('_controller'), ':') !== false) {
            list($controller, $action) = explode(':', $route->getDefault('_controller'));
        } else {
            $controller = $route->getDefault('_controller');
            $action = 'index';
        }

        if ($this->container->has($controller)) {
            $controllerObj = $this->container->get($controller);
        } else {
            $controllerObj = new $controller();
        }

        $entityClass = null;
        if (defined(get_class($controllerObj) . '::ENTITY_CLASS')) {
            $entityClass = $controllerObj::ENTITY_CLASS;
        }

        if (defined(get_class($controllerObj) . '::DTO_CLASS')) {
            $entityClass = $controllerObj::DTO_CLASS;
        }

        if ($controllerObj instanceof DynamicEntityClassControllerInterface) {
            $entityClass = $controllerObj->getEntityClass();
        }

        if ($controllerObj instanceof EnumController) {
            foreach ($this->getOperations($api, $route) as $annotation) {
                /** @var EnumService $enumService */
                $enumService = $this->container->get('App\Service\CRUD\EnumService');
                $dataSort = $enumService->extractClasses(null);
                sort($dataSort);
                $toString = '<ol>';
                foreach ($dataSort as $extractClass) {
                    $toString .= "<li>$extractClass</li>";
                }
                $toString .= '</ol>';

                $annotation->setDescription($annotation->getDescription() . ",\n ### Доступные Enum:\n " . $toString);
            }
        }
        if (!$entityClass) {
            return;
        }

        $model = new Model(new Type(Type::BUILTIN_TYPE_OBJECT, false, $entityClass));

        $refClassController = new ReflectionClass($controllerObj);
        $method = $refClassController->getMethod($action);

        /** @var Operation|null $operationMethodAnnotation */
        $operationMethodAnnotation = $this->annotationReader->getMethodAnnotation($method, Operation::class);

        /** @var Resource $resource */
        $resource = $this->annotationReader->getClassAnnotation($refClassController, Resource::class);
        $context = new Context([
            'namespace' => $method->getNamespaceName(),
            'class' => $refClassController->getShortName(),
            'method' => $method->name,
            'filename' => $method->getFileName(),
        ]);

        if ($resource && !empty($resource->modelDefinitionName)) {
            $reflectionModelRegistry = new ReflectionClass($this->modelRegistry);

            $propertyNamesReflection = $reflectionModelRegistry->getProperty('names');
            $propertyNamesReflection->setAccessible(true);
            $names = $propertyNamesReflection->getValue($this->modelRegistry);
            $names[$model->getHash()] = $resource->modelDefinitionName;
            $propertyNamesReflection->setValue($this->modelRegistry, $names);

            $propertyModelsReflection = $reflectionModelRegistry->getProperty('models');
            $propertyModelsReflection->setAccessible(true);
            $models = $propertyModelsReflection->getValue($this->modelRegistry);
            $models[$model->getHash()] = $model;
            $propertyModelsReflection->setValue($this->modelRegistry, $models);

            $propertyUnregisteredReflection = $reflectionModelRegistry->getProperty('unregistered');
            $propertyUnregisteredReflection->setAccessible(true);
            $unregistered = $propertyUnregisteredReflection->getValue($this->modelRegistry);
            $unregistered[] = $model->getHash();
            $propertyUnregisteredReflection->setValue($this->modelRegistry, $unregistered);

            //Reserve the name
            $api->getDefinitions()->get($resource->modelDefinitionName);
        }

        $definition = $this->modelRegistry->register($model);

        $nestedContext = clone $context;
        $nestedContext->nested = true;
        $description = $resource ? $resource->getDescription() : '';

        foreach ($this->getOperations($api, $route) as $annotation) {
            if ($resource) {
                if ($operationMethodAnnotation) {
                    $operationId = $operationMethodAnnotation->id;
                } else {
                    $operationId = $annotation->getOperationId();
                }

                $reflectionAnnotation = new ReflectionClass($annotation);
                $annotation->setDeprecated($resource->isDeprecated($operationId));

                /** @var Response $response */
                foreach ($annotation->getResponses() as $identity => $response) {
                    switch ($operationId) {
                        case 'list':
                            $modelClass = BaseListResponse::class;
                            $pseudoType = $resource->listResponseModel;
                            $postfix = 'List';
                            break;

                        case 'get':
                        case 'put':
                        case 'post':
                        case 'patch':
                            $modelClass = BaseItemResponse::class;
                            $pseudoType = $resource->responseModel;
                            $postfix = ucfirst($operationId);
                            break;

                        default:
                            continue 2;
                    }

                    if (($identity == 200 || $identity == 201)) {
                        if (null === $pseudoType) {
                            $pseudoType = $entityClass;
                        }

                        $classPseudoTypeInfo = explode('\\', $pseudoType);
                        $entityName = end($classPseudoTypeInfo);

                        if ($resource->modelDefinitionName) {
                            $entityName = $resource->modelDefinitionName;
                        }

                        $modelName = sprintf('%s%sResponse', $entityName, $postfix);

                        $modelResponse = new \App\Model\Model(new Type(
                            Type::BUILTIN_TYPE_OBJECT,
                            false,
                            $modelClass
                        ), [], ['pseudoType' => $pseudoType, 'modelName' => $modelName, 'entityName' => $entityName]);

                        $names = PropertyAccessor::getValueForce($this->modelRegistry, 'names');
                        $names[$modelResponse->getHash()] = $modelName;
                        PropertyAccessor::setValueForce($this->modelRegistry, 'names', $names);

                        $models = PropertyAccessor::getValueForce($this->modelRegistry, 'models');
                        $models[$modelResponse->getHash()] = $modelResponse;
                        PropertyAccessor::setValueForce($this->modelRegistry, 'models', $models);

                        $unregistered = PropertyAccessor::getValueForce($this->modelRegistry, 'unregistered');
                        $unregistered[] = $modelResponse->getHash();
                        PropertyAccessor::setValueForce($this->modelRegistry, 'unregistered', $unregistered);

//                    Reserve the name
                        $api->getDefinitions()->get($modelName);
                        $definitionResponseModel = $this->modelRegistry->register($modelResponse);

                        $stdClass = new stdClass();
                        $stdClass->{'$ref'} = $definitionResponseModel;

                        $schema = new Schema();
                        $schema->setRef($definitionResponseModel);
                        PropertyAccessor::setValueForce($response, 'schema', $stdClass);
                        $api->getDefinitions()->merge($modelResponse);
                    }

                }

                if ($specificDescription = $resource->getDescriptionMap($operationId)) {
                    $annotation->setDescription(
                        $specificDescription ?? $annotation->getDescription() ?? $description
                    );
                }

                if ($summary = $resource->getSummary($operationId)) {
                    $annotation->setSummary($summary ?? $annotation->getSummary());
                }

                $reflectionTagsProperty = $reflectionAnnotation->getProperty('tags');
                $reflectionTagsProperty->setAccessible(true);

                $alreadyAnnotationTags = $reflectionTagsProperty->getValue($annotation);
                $newTags = array_merge((array)$alreadyAnnotationTags, $resource->getTags() ?: []);
                $reflectionTagsProperty->setValue($annotation, $newTags);
            }

            /** @var Parameter $parameter */
            foreach ($annotation->getParameters() as $parameter) {
                if ($parameter->getName() == 'entity') {
                    $parameter->_unmerged[] = $model;

                    if ($parameter->getSchema() && !$parameter->getSchema()->getRef()) {
                        $parameter->getSchema()->setRef($definition);
                    }
                }
            }

            $api->getDefinitions()->merge($model);
        }
    }

    private function createType(string $type): Type
    {
        if ('[]' === substr($type, -2)) {
            return new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, null, $this->createType(substr($type, 0, -2)));
        }

        return new Type(Type::BUILTIN_TYPE_OBJECT, false, $type);
    }
}
