<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepo extends BaseRepo
{
    protected function model(): string
    {
        return Currency::class;
    }
}
