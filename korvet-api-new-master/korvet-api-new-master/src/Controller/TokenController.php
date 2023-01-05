<?php

namespace App\Controller;

use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Class TokenController
 */
class TokenController
{
    /** @var OAuth2 */
    protected OAuth2 $server;

    /** @var TranslatorInterface  */
    protected TranslatorInterface $translator;

    /**
     * @param OAuth2 $server
     * @param TranslatorInterface $translator
     */
    public function __construct(OAuth2 $server, TranslatorInterface $translator)
    {
        $this->server = $server;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     *
     * @param BaseResponse $response
     * @return Response
     */
    public function tokenAction(Request $request, BaseResponse $response)
    {
        try {
            return $this->server->grantAccessToken($request);
        } catch (OAuth2ServerException $e) {
            $oauthErrorInfo = json_decode($e->getResponseBody(), true);

            $response
                ->statusError()
                ->addError(
                    new ApiException(
                        $this->translator->trans($oauthErrorInfo['error_description'], [], 'auth'),
                        $oauthErrorInfo['error'],
                        null,
                        $e->getHttpCode()
                    )
                );

            return $response->send();
        }
    }
}
