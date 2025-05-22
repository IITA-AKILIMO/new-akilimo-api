<?php

namespace App\Repositories;

use App\Models\ApiRequest;

/**
 * @extends \App\Repositories\BaseRepository<ApiRequest>
 */
class ApiRequestRepo extends \App\Repositories\BaseRepository
{
    protected function model(): string
    {
        return ApiRequest::class;
    }
}
