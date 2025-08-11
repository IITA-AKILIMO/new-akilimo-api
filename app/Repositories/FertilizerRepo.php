<?php

namespace App\Repositories;

use App\Models\Fertilizer;

/**
 * @extends BaseRepo<Fertilizer>
 */
class FertilizerRepo extends BaseRepo
{
    protected function model(): string
    {
        return Fertilizer::class;
    }
}
