<?php

namespace App\Interfaces;

use App\Packages\Response\BaseResponse as ApiResponse;

/**
 * Interface StrategyResponseInterface
 */
interface StrategyResponseInterface
{
    /**
     * @param ApiResponseInterface $dirtyResponse
     * @return ApiResponse
     */
    public function getResponse(ApiResponseInterface $dirtyResponse);
}
