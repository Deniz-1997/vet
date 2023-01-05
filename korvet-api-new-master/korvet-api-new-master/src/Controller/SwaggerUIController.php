<?php

namespace App\Controller;

use App\Model\Env;
use EXSyst\Component\Swagger\Collections\Paths;
use Nelmio\ApiDocBundle\ApiDocGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Packages\Utils\PropertyAccessor;
use Twig\Environment;

/**
 * Class SwaggerUIController
 */
final class SwaggerUIController extends AbstractController
{
    use DocumentationControllerTrait;

    private ApiDocGenerator $apiDocGenerator;
    private Environment $twig;

    public function __construct(ApiDocGenerator $apiDocGenerator, Environment $twig)
    {
        $this->apiDocGenerator = $apiDocGenerator;
        $this->twig = $twig;
    }

    public function __invoke(Request $request)
    {
        $api = $this->apiDocGenerator->generate();

        $baseURL = $api->getBasePath();
        if (!$baseURL) {
            $baseURL = $request->getBaseUrl();
        }

        $schema = $request->isSecure() ? 'https' : 'http';
        if ($request->getPort() === 443) {
            $schema = 'https';
        }

        $api = $this->preparePaths($baseURL, $api);

        $specArrayForTemplate = $api->toArray();
        if ('' !== $request->getBaseUrl()) {
            $specArrayForTemplate['basePath'] = $request->getBaseUrl();
        }

        $clientId = Env::getenv('MICROSERVICE_CLIENT_ID');
        $clientSecret = Env::getenv('MICROSERVICE_CLIENT_SECRET');

        return $this->render('@WebslonApi/SwaggerUi/index.html.twig', [
            'swagger_data' => ['spec' => $specArrayForTemplate],
            'schema' => $schema,
            'clientSecret' => $clientSecret,
            'clientId' => $clientId,
        ]);
    }
}
