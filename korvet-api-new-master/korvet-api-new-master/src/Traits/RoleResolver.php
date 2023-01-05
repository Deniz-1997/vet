<?php

namespace App\Traits;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Packages\Security\Encryptor;

trait RoleResolver
{
    /** @var Encryptor */
    private Encryptor $encryptor;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /**
     * @required
     * @param Encryptor $encryptor
     */
    public function setEncryptor(Encryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * @required
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function resolveDefaultRoles(Request $request): array
    {
        $roles = [];

        if ($signature = $request->headers->get('X-Microservice-Signature')) {
            try {
                $microserviceCode = $this->encryptor->dencrypt($signature);
                $roleFormat = mb_strtoupper(str_replace('-', '_', $microserviceCode));

                $roles[] = 'ROLE_'.$roleFormat;
            } catch (\Exception $exception) {
                $this->logger->error('Cannot decode microservice signature', ['ex' => $exception, 'signature' => $signature]);
            }
        }

        return $roles;
    }
}
