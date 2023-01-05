<?php

namespace App\Packages\Fetcher;

use App\Entity\Embeddable\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EntityFetcher
 */
class EntityFetcher
{
    /** @var RouterInterface */
    private $router;
    /** @var TranslatorInterface */
    private $translator;
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * EntityFetcher constructor.
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Entity[]
     */
    public function getEntities() : array
    {
        $entities = [];

        $routes = $this->router->getRouteCollection()->all();
        foreach ($routes as $route) {
            $controller = $route->getDefault('_controller');
            if (strpos($controller, '::') !== false) {
                $controllerInfo = explode('::', $controller);
                $methods = $route->getMethods();

                if ($methods && \defined($controllerInfo[0] . '::ENTITY_CLASS')) {
                    $entityClass = $controllerInfo[0]::ENTITY_CLASS;
                    if (!$this->isEntity($entityClass)) {
                        continue;
                    }

                    $entityClassInfo = explode('\\', $entityClass);
                    $entityClassWithoutNamespace = array_pop($entityClassInfo);

                    if (!isset($entities[$entityClass])) {
                        $entity = new Entity();
                        $entity->setName(
                            $this->translator->trans($entityClassWithoutNamespace, [], 'classes')
                        );
                        $entity->setClassName($entityClass);

                        $entities[$entityClass] = $entity;
                    }
                }
            }
        }

        return array_values($entities);
    }

    /**
     * @param string $entityClass
     * @return boolean
     */
    private function isEntity(string $entityClass) : bool
    {
        try {
            return !is_null($this->entityManager->getClassMetadata($entityClass));
        } catch (\Exception $exception) {
            return false;
        }
    }
}
