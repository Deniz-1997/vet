<?php

namespace App\Packages\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Resource
{
    /**
     * @var array массив тегов
     */
    public array $tags = [];

    /**
     * @var string описание
     */
    public string $description = '';

    /**
     * @var array
     */
    public array $summariesMap = [];

    /**
     * @var string
     */
    public string $modelDefinitionName;

    /**
     * @var array описание для различных методов
     */
    public array $descriptionsMap = [];

    /**
     * @var array устаревшие функции, которые нужно пометить как deprecated
     */
    public array $deprecatedMap = [];

    /**
     * @var array возможные ответы
     */
    public array $responsesMap = [];

    /**
     * @var string class name
     */
    public string $listResponseModel;

    /**
     * @var string class name
     */
    public string $responseModel;

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getDescriptionsMap(): array
    {
        return $this->descriptionsMap;
    }

    public function getDescriptionMap($id): string
    {
        return !empty($this->descriptionsMap[$id]) ? $this->descriptionsMap[$id] : '';
    }

    public function getSummary($id): string
    {
        return !empty($this->summariesMap[$id]) ? $this->summariesMap[$id] : '';
    }

    public function getResponses($id): string
    {
        return !empty($this->responsesMap[$id]) ? $this->responsesMap[$id] : '';
    }

    public function isDeprecated($id): bool
    {
        return is_array($this->deprecatedMap) ? array_search($id, $this->deprecatedMap) !== false : false;
    }
}
