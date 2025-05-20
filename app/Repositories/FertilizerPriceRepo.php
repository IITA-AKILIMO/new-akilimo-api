<?php

namespace App\Repositories;

use App\Models\FertilizerPrice;

class FertilizerPriceRepo extends BaseRepository
{
    protected function model(): string
    {
        return FertilizerPrice::class;
    }

    public function findBySortOrderAndFertilizerKey(int $sortOrder, string $fertilizerKey)
    {

        return FertilizerPrice::where('sort_order', $sortOrder)
            ->where('fertilizer_key', $fertilizerKey)
            ->first();
    }
}
