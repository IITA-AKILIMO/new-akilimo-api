<?php

namespace App\Repositories;

use App\Models\OperationCost;
use App\Models\StarchFactory;


class StarchFactoryRepo extends BaseRepo
{
    protected function model(): string
    {
        return StarchFactory::class;
    }
}
