<?php

namespace App\Repositories;


use App\Models\DefaultPrice;

/**
 * @extends BaseRepo<DefaultPrice>
 */
class DefaultPriceRepo extends BaseRepo
{

    protected function model(): string
    {
        return DefaultPrice::class;
    }
}
