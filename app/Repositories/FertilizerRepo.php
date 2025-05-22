<?php

namespace App\Repositories;

use App\Models\Fertilizer;

/**
 * @extends \App\Repositories\BaseRepository<Fertilizer>
 */
class FertilizerRepo extends \App\Repositories\BaseRepository
{
    protected function model(): string
    {
        return Fertilizer::class;
    }
}
