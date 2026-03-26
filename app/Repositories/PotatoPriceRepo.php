<?php

namespace App\Repositories;

use App\Models\PotatoPrice;

class PotatoPriceRepo extends BaseRepo
{
    protected function model(): string
    {
        return PotatoPrice::class;
    }
}
