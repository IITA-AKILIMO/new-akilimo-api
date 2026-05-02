<?php

namespace App\Repositories;

use App\Models\DefaultPrice;
use Illuminate\Database\Eloquent\Collection;

class DefaultPriceRepo extends BaseRepo
{
    protected function model(): string
    {
        return DefaultPrice::class;
    }

    /**
     * @return Collection<int, DefaultPrice>
     */
    public function forCountry(string $country): Collection
    {
        return $this->query()
            ->where('country', $country)
            ->orderBy('item')
            ->get();
    }

    public function deleteByIds(array $ids, string $country): void
    {
        if (empty($ids)) {
            return;
        }

        $this->query()
            ->whereIn('id', $ids)
            ->where('country', $country)
            ->delete();
    }
}
