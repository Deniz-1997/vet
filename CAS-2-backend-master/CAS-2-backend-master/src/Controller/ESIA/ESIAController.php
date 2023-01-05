<?php


namespace App\Controller\ESIA;

use App\Interfaces\ESIA\ESIAInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Packages\Response\BaseResponse as ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\ApiException;

/**
 * Class ESIAController
 * 
 * @Route("/api/esia")
 */
class ESIAController extends AbstractController
{
    /** 
     * @var ApiResponse 
     */
    private ApiResponse $response;

    /** 
     * @var ESIAInterface 
     */
    private ESIAInterface $esiaService;

    /**
     * ESIAController constructor.
     * @param ESIAInterface $esiaService
     * @param ApiResponse $response
     */
    public function __construct(ESIAInterface $esiaService, ApiResponse $response)
    {
        $this->esiaService = $esiaService;
        $this->response = $response;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function EsiaRoute()
    {
        return $this->response->setResponse($this->esiaService->GetAuthorizationUrl())->send();
    }
    /**
     * @Route("/data/", methods={"GET"})
     * @param Request $request
     */
    public function EsiaData(Request $request)
    {
        try {
            $code = $request->get('code');
            $token = $this->esiaService->AuthorizeUser($code);
            
            return $this->response->setResponse($token)->send();
        } catch (\Exception $ex) {
            throw new ApiException($ex->getMessage(), 'ACCESS_DENIED', null, 400);
        }
       
    }
}

