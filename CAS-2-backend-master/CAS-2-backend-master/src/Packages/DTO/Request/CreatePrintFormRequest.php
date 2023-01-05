<?php

namespace App\Packages\DTO\Request;

use OpenApi\Annotations as SWG;

/**
 * Class CreatePrintFormRequest
 */
class CreatePrintFormRequest
{
    /**
     * @var int
     * @SWG\Property(type="integer")
     */
    public int $petId;

    /**
     * @var int
     * @SWG\Property(type="integer")
     */
    public int $ownerId;

    /**
     * @var int
     * @SWG\Property(type="integer")
     */
    public int $appointmentId;
}
