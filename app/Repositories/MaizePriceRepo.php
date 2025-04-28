<?php

namespace App\Repositories;


use App\Data\MinMaxPriceDto;
use App\Models\MaizePrice;

class MaizePriceRepo
{
    protected MaizePrice $model;

    public function __construct()
    {
        $this->model = new MaizePrice();
    }


    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function selectOne(array $conditions)
    {
        return $this->model->where($conditions)->first();
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        if (!$record) {
            return null;
        }

        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        return $record->delete();
    }

    /**
     * Retrieves the minimum and maximum price entity for a given country code.
     *
     * @param string $countryCode The country code used to filter the prices.
     * @return MinMaxPriceDto Contains the minimum and maximum local prices for the specified country.
     */
    public function findPriceBandsByCountryCode(string $countryCode,string $produceType): MinMaxPriceDto
    {
        $minPrice = $this->model->whereCountry($countryCode)
            ->where('min_local_price', '>', 0)
            ->where('produce_type', $produceType)
            ->min('min_local_price');

        $maxPrice = $this->model->whereCountry($countryCode)
            ->where('produce_type', $produceType)
            ->max('max_local_price');

        return new MinMaxPriceDto($minPrice, $maxPrice);
    }
}
