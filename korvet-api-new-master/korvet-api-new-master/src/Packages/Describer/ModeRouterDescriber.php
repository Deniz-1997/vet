<?php


namespace App\Packages\Describer;


use App\Packages\Annotation\Operation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Swagger;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\OpenApi;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

class ModeRouterDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;

    /** @var AnnotationReader */
    private $annotationReader;

    /** @var ContainerInterface */
    private $container;

    /**
     * ModeRouterDescriber constructor.
     * @param AnnotationReader $annotationReader
     * @param ContainerInterface $container
     */
    public function __construct(Reader $annotationReader, ContainerInterface $container)
    {
        $this->annotationReader = $annotationReader;
        $this->container = $container;
    }

    public function describe(OpenApi $api, Route $route, \ReflectionMethod $reflectionMethod)
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
        if (\defined(\get_class($controllerObj) . '::ENTITY_CLASS')) {
            $entityClass = $controllerObj::ENTITY_CLASS;
        }

        if (\defined(\get_class($controllerObj) . '::DTO_CLASS')) {
            $entityClass = $controllerObj::DTO_CLASS;
        }

        if (!$entityClass) {
            return;
        }

        $refClassController = new \ReflectionClass($controllerObj);
        $method = $refClassController->getMethod($action);
        $operationMethodAnnotation = $this->annotationReader->getMethodAnnotation($method, Operation::class);

        /** @var Operation $annotation */
        foreach ($this->getOperations($api, $route) as &$annotation) {
            if ($operationMethodAnnotation) {
                $operationId = $operationMethodAnnotation->id;
            } else {
                $operationId = $annotation->getOperationId();
            }

            switch ($operationId) {
                case 'list':
                    $annotation->getParameters()->add(new Parameter([
                        'name' => 'mode',
                        'in' => 'query',
                        'description' => 'Какие данные требуется вывести: ["layout"]',
                        'type' => 'string',
                        'default' => ''
                    ]));
                    break;

                case 'get':
                    $annotation->getParameters()->add(new Parameter([
                        'name' => 'mode',
                        'in' => 'query',
                        'description' => 'Какие данные требуется вывести: ["actions", "listActions", "data", "layout"]',
                        'type' => 'string',
                        'default' => ''
                    ]));
                    break;
            }
        }
    }
}
