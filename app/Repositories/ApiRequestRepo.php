<?php

namespace App\Repositories;

use App\Models\ApiRequest;

/**
 * @extends BaseRepository<ApiRequest>
 */
class ApiRequestRepo extends BaseRepository
{

    /**
     * @return class-string<ApiRequest>
     */
    protected function model(): string
    {
        return ApiRequest::class;
    }
}
