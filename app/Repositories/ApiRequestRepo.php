<?php

namespace App\Repositories;

use App\Models\ApiRequest;

/**
 * @extends BaseRepo<ApiRequest>
 */
class ApiRequestRepo extends BaseRepo
{

    /**
     * @return class-string<ApiRequest>
     */
    protected function model(): string
    {
        return ApiRequest::class;
    }
}
