<?php

namespace App\Service;

class ResourcePathService
{
    /**
     * @var string
     */
    private string $themePath;

    /**
     * @var string
     */
    private string $templatePath;

    public function __construct(string $themePath, string $templatePath)
    {
        $this->themePath = $themePath;
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getThemePath(string $fileName): string
    {
        return $this->themePath . $fileName;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getTemplatePath(string $fileName): string
    {
        return $this->templatePath . $fileName;
    }
}
