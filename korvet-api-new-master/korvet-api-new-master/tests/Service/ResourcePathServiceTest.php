<?php

namespace App\Tests\Service;

use App\Service\ResourcePathService;
use PHPUnit\Framework\TestCase;

class ResourcePathServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $themePath;

    /**
     * @var string
     */
    private string $templatePath;

    /**
     * @var ResourcePathService
     */
    private $service;

    protected function setUp(): void
    {
        $this->themePath = '/this/is/some/path/to/theme/';
        $this->templatePath = '/this/is/some/path/to/template/';

        $this->service = new ResourcePathService($this->themePath,
            $this->templatePath);
    }

    public function testThemePath(): void
    {
        $themeName = 'theme.html.twig';

        $this->assertSame($this->themePath . $themeName,
            $this->service->getThemePath($themeName));
    }

    public function testTemplatePath(): void
    {
        $templateName = 'template.html.twig';

        $this->assertSame($this->themePath . $templateName,
            $this->service->getThemePath($templateName));
    }
}
