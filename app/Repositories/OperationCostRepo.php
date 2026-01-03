<?php

namespace App\Repositories;

use App\Models\OperationCost;


class OperationCostRepo extends BaseRepo
{
    protected function model(): string
    {
        return OperationCost::class;
    }
}
