<?php

namespace App\Service\CRUD;

use App\Interfaces\EnumInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Error;
use Exception;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Finder\Finder;

use Symfony\Contracts\Translation\TranslatorInterface;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;
use Throwable;
use function is_string;

/**
 * Class EnumService
 */
class EnumService
{
    /**
     * Namespaces Enum
     *
     * @var array
     */
    private array $dirs = [];

    /**
     * @var Finder
     */
    private Finder $finder;

    /**
     * @var AnnotationReader
     */
    private AnnotationReader $readerAnnotation;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var string
     */
    private string $rootDir;

    /**
     * EnumService constructor.
     *
     * @param array $dirs
     * @param AnnotationReader $readerAnnotation
     * @param TranslatorInterface $translator
     * @param string $rootDir
     */
    public function __construct(array $dirs, AnnotationReader $readerAnnotation, TranslatorInterface $translator, string $rootDir)
    {
        $this->dirs = $dirs;
        $this->rootDir = $rootDir;
        $this->finder = new Finder();
        $this->readerAnnotation = $readerAnnotation;
        $this->translator = $translator;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getAll(): array
    {
        $result = $items = [];
        $classes = $this->extractClasses();
        /** @var ReflectionClass $class */
        foreach ($classes as $class) {
            try {
                /** @var EnumInterface $className */
                $className = $class->getName();
                $isSupport = $this->readerAnnotation->getClassAnnotation($class, EnumAnnotation::class) || is_subclass_of($className, EnumInterface::class);

                if ($isSupport) {
                    $its = [];
                    foreach ($className::choices() as $code => $transKey) {
                        $its[] = ['id' => $code === Enum::NULLABLE ? null : $code, 'name' => $this->translator->trans($transKey, [], 'enum')];
                    }
                    $items[] =
                        [
                            'id' => $class->getShortName(),
                            'fullId' => $class->getNamespaceName() . '\\' . $class->getShortName(),
                            'items' => $its,
                        ];
                }
            } catch (RuntimeException | Error | Exception $e) {
                continue;
            }
        }
//        VarDumper::dump($items);
//        die();
        $result['response'] = $items;

        return $result;
    }

    /**
     * @return ArrayCollection
     * @throws ReflectionException
     */
    public function getCollection(): ArrayCollection
    {
        $collection = new ArrayCollection();
        foreach ($this->getAll()['response'] as $item) {
            if (is_array($item)) {
                $collection->add($item);
            }
        }

        return $collection;
    }

    /**
     * @param array $filter
     *
     * @return array
     * @throws ReflectionException
     */
    public function getItemsByFilter(array $filter): array
    {
        $data = $this->getCollection();
        $code = null;
        if (isset($filter['items']['id'])) {
            $code = $filter['items']['id'];
        }
        $name = null;
        if (isset($filter['items']['name'])) {
            $name = $filter['items']['name'];
        }
        unset($filter['items']);

        $criteria = $this->createCriteria($filter);
        $result = [];
        $items = [];
        if ($criteria instanceof Criteria) {
            $res = $data->matching($criteria);
            if ($res->count() > 1) {
                foreach ($res->toArray() as $item) {
                    $items[] = $item;
                }
            } else {
                if ($res->count() > 0) {
                    $items[] = $res->first();
                }
            }
        }
        if ($code || $name) {
            foreach ($data as $itemCollection) {
                foreach ($itemCollection['items'] as $item) {
                    if (is_array($name)) {
                        foreach ($name as $itemName) {
                            if ($item['name'] === $itemName) {
                                $items[$itemCollection['fullId']] = $itemCollection;
                            }
                        }
                    } else {
                        if ($name === $item['name']) {
                            $items[$itemCollection['fullId']] = $itemCollection;
                        }
                    }

                    if (is_array($code)) {
                        foreach ($code as $itemId) {
                            if ($item['id'] === $itemId) {
                                $items[$itemCollection['fullId']] = $itemCollection;
                            }
                        }
                    } else {
                        if ($code === $item['id']) {
                            $items[$itemCollection['fullId']] = $itemCollection;
                        }
                    }
                }
            }
        }
        $result['response'] = array_values($items);

        return $result;
    }

    /**
     * @param array $filter
     * @return Criteria|void
     */
    private function createCriteria(array $filter): ?Criteria
    {
        if (!$filter) {
            return null;
        }
        unset($filter['LOGIC']);
        $criteria = Criteria::create();
        $conditions = [];
        foreach ($filter as $field => $value) {
            if ($field === 'items' || $field === 'LOGIC') {
                continue;
            }
            $op = $this->getOperator($field);
            $field = $this->clearValue($field);
            if (is_array($value)) {
                if ($op === '=') {
                    $conditions[] = Criteria::expr()->in($field, $value);
                } else {
                    $conditions[] = Criteria::expr()->notIn($field, $value);
                }
            } else {
                if ($op === '=') {
                    $conditions[] = Criteria::expr()->eq($field, $value);
                } else {
                    $conditions[] = Criteria::expr()->neq($field, $value);
                }
            }
        }

        foreach ($conditions as $expression) {
            $criteria->andWhere($expression);
        }

        return $criteria;
    }

    /**
     * @param string $id
     * @return array
     * @throws ReflectionException
     */
    public function getItemById(string $id): array
    {
        $data = $this->getCollection();

        $where = Criteria::expr()->eq('fullClassName', $id);
        $orWhere = Criteria::expr()->eq('shortClassName', $id);
        $cr = Criteria::create()->where($where)->orWhere($orWhere);

        $result = [
            'response' => [],
        ];
        if ($item = $data->matching($cr)->first()) {
            $result['response'] = $item;
        }
        if (!$result['response']) {
            foreach ($data as $itemCollection) {
                if ($itemCollection['code'] === null) {
                    continue;
                }
                if (in_array($id, $itemCollection['code']) !== false) {
                    $result['response'] = $itemCollection;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $format
     *
     * @return array|null
     * @throws ReflectionException
     */
    public function extractClasses(string $format = 'reflection'): ?array
    {
        $isInFinder = false;
        foreach ($this->dirs as $dir) {
            try {
                $this->finder->in($dir);
                $isInFinder = true;
            } catch (Throwable $exception) {

            }
        }
        if (!$isInFinder) {
            return [];
        }
        if (!$this->finder->hasResults()) {
            return [];
        }
        $reflections = [];
        foreach ($this->finder as $file) {
            if (!is_file($file)) {
                continue;
            }
            $filename = str_replace('.php', '', $file->getFilename());
            $classWithNamespace = $this->getFullNamespace(realpath($file->getRealPath())) . '\\' . $filename;
            $reflectionClass = new ReflectionClass($classWithNamespace);
            if ($format === 'reflection') {
                $reflections[] = $reflectionClass;
            } else {
                $reflections[] = $reflectionClass->getShortName();
            }
        }

        return $reflections;
    }

    /**
     * @param string $phpFile
     *
     * @return mixed
     */
    private function getFullNamespace(string $phpFile)
    {
        $lines = preg_grep('/^namespace /', file($phpFile));
        $namespaceLine = array_shift($lines);
        return trim(str_replace(['namespace',';'], '', $namespaceLine));
    }

    /**
     * @param array|string $column
     *
     * @return string|null
     */
    private function getOperator($column): ?string
    {
        $op = '=';
        if (is_array($column)) {
            foreach ($column as $name => $value) {
                if (preg_match('/(\=|\<\=|\>\=|\!\=|<|>|\!)/', $name, $m)) {
                    $op = $m[1];
                }
                if (is_array($value)) {
                    $op = $this->getOperator($value);
                }
            }
        } else {
            if (preg_match('/(\=|\<\=|\>\=|\!\=|<|>|\!)/', $column, $m)) {
                $op = $m[1];
            }
        }

        return $op;
    }

    /**
     * @param array|string $filterValue
     *
     * @return array|string
     */
    private function clearValue($filterValue)
    {
        $findBy = [];
        if (is_string($filterValue)) {
            return $this->clearOperatorColumn($filterValue);
        }
        if (is_array($filterValue)) {
            foreach ($filterValue as $k => $v) {
                if (is_string($k)) {
                    $findBy[$this->clearOperatorColumn($k)] = $v;
                }
            }
        }

        if (empty($findBy)) {
            $findBy = $filterValue;
        }

        return $findBy;
    }

    /**
     * @param string $column
     *
     * @return string|null
     */
    private function clearOperatorColumn(string $column): ?string
    {
        return preg_replace('/(\=|\<\=|\>\=|\!\=|<|>|\!)/', '', $column);
    }
}
