<?php

namespace App\Controller;

use App\Model\Env;
use App\Packages\Client\AccountClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Packages\Security\Token\OAuthPreAuthenticatedToken;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @Route("/api/oauth-redirect")
 * Class OAuthRedirectController
 */
class OAuthRedirectController extends AbstractController
{
    public function __invoke(Request $request, AccountClient $accountClient, EventDispatcherInterface $eventDispatcher, SessionInterface $sessionStorage)
    {
        $formData = [
            'grant_type' => 'authorization_code',
            'code' => $request->query->get('code'),
            'client_id' => Env::getenv('MICROSERVICE_CLIENT_ID'),
            'client_secret' => Env::getenv('MICROSERVICE_CLIENT_SECRET'),
            'redirect_uri' => $request->getScheme().':'.$this->generateUrl('webslon_auth.swagger.oauth_redirect', [], UrlGeneratorInterface::NETWORK_PATH)
        ];

        $tokenUrl = Env::getenv('SECURITY_ADDRESS').'/api/oauth/v2/token/?'.http_build_query($formData);
        $response = file_get_contents($tokenUrl, false, stream_context_create([
            'http' => ['ignore_errors' => true],
        ]));

        $oauthServerData = json_decode($response, true);
        $data = new ParameterBag($oauthServerData ?: []);
        if (!$data->has('access_token')) {
            throw new BadRequestHttpException($response);
        }

        if (!$accountClient->getCurrentAuthenticationInfo($data->get('access_token'))) {
            throw new BadRequestHttpException('Cloud not retrieve information from OAuth response');
        }

        $request->getSession()->set('_swagger_oauth_data', json_encode($oauthServerData));

        if ($successRoute = $this->getParameter('webslon_auth.oauth2_success_login_route')) {
            return $this->redirectToRoute($successRoute);
        }

        return RedirectResponse::create('/');
    }
}
