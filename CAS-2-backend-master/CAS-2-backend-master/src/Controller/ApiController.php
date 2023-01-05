<?php

namespace App\Controller;

use App\Interfaces\ApiControllerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller implements ApiControllerInterface
{
    /**
     * @param $method
     * @return array
     */
    public function getSerializationContext($method): array
    {
        $serializationContext = [];

        $serializationContextOptions = $this->getSerializationContextOptions();
        if (\is_array($serializationContextOptions) && isset($serializationContextOptions[$method])) {
            $serializationContext = $serializationContextOptions[$method];
        }

        return $serializationContext;
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions(): array
    {
        return [];
    }
}
