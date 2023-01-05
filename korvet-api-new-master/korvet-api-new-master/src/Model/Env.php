<?php

namespace App\Model;

use App\Entity\HistoryEntity;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class Env
 */
class Env
{

    /**
     * @param string
     * @return string|null
     */
    static public function getenv(string $args)
    {
     return $_ENV[$args] ?? null;
    }
}
