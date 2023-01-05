<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 2/24/18
 * Time: 11:33 PM
 */

namespace App\Controller;

use EXSyst\Component\Swagger\Collections\Paths;
use Nelmio\ApiDocBundle\ApiDocGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Packages\Utils\PropertyAccessor;

class DocumentationController
{
    use DocumentationControllerTrait;

    private ApiDocGenerator $apiDocGenerator;

    public function __construct(ApiDocGenerator $apiDocGenerator)
    {
        $this->apiDocGenerator = $apiDocGenerator;
    }

    public function __invoke(Request $request)
    {
        $spec = $this->apiDocGenerator->generate();

        $baseURL = $spec->getBasePath();
        if (!$baseURL) {
            $baseURL = $request->getBaseUrl();
        }

        $spec = $this->preparePaths($baseURL, $spec);

        $specArray = $spec->toArray();
        if ('' !== $request->getBaseUrl()) {
            $specArray['basePath'] = $request->getBaseUrl();
        }

        return new JsonResponse($specArray);
    }
}
