<?php

namespace App\Repositories;

use App\Models\FertilizerPrice;

class FertilizerPriceRepo extends BaseRepo
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

    /**
     * Load min (sort_order=1) and max (sort_order=4) price band records for a list of
     * fertilizer keys in two queries instead of two queries per row.
     *
     * @param  string[]  $keys
     * @return array<string, array{min: FertilizerPrice|null, max: FertilizerPrice|null}>
     */
    public function findMinMaxBandsByKeys(array $keys): array
    {
        $minBands = FertilizerPrice::where('sort_order', 1)
            ->whereIn('fertilizer_key', $keys)
            ->get()
            ->keyBy('fertilizer_key');

        $maxBands = FertilizerPrice::where('sort_order', 4)
            ->whereIn('fertilizer_key', $keys)
            ->get()
            ->keyBy('fertilizer_key');

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = [
                'min' => $minBands->get($key),
                'max' => $maxBands->get($key),
            ];
        }

        return $result;
    }
}
