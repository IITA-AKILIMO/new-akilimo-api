<?php

namespace App\Repositories;

use App\Models\OperationCost;


class OperationCostRepo extends BaseRepository
{
    protected function model(): string
    {
        return OperationCost::class;
    }
}
