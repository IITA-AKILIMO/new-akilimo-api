<?php

namespace App\Repositories;

use App\Models\Fertilizer;

/**
 * @extends \App\Repositories\BaseRepo<Fertilizer>
 */
class FertilizerRepo extends \App\Repositories\BaseRepo
{
    protected function model(): string
    {
        return Fertilizer::class;
    }
}
