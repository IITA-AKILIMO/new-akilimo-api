<?php

namespace App\Repositories;

use App\Models\InvestmentAmount;

class InvestmentRepo extends BaseRepo
{
    protected function model(): string
    {
        return InvestmentAmount::class;
    }
}
