<?php

namespace App\Repositories;


use App\Models\StarchPrice;

/**
 * @extends BaseRepo<StarchPrice>
 */
class StarchPriceRepo extends BaseRepo
{
    protected function model(): string
    {
        return StarchPrice::class;
    }
}
