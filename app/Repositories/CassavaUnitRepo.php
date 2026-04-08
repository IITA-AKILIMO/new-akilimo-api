<?php

namespace App\Repositories;

use App\Models\CassavaUnit;

class CassavaUnitRepo extends BaseRepo
{
    /**
     * @return class-string<CassavaUnit>
     */
    protected function model(): string
    {
        return CassavaUnit::class;
    }
}
