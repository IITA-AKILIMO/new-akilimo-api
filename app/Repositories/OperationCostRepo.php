<?php

namespace App\Repositories;

use App\Data\MinMaxPriceDto;
use App\Models\OperationCost;
use App\Repositories\Contracts\Repository;
use Illuminate\Database\Eloquent\Collection;


class OperationCostRepo implements Repository
{
    protected OperationCost $model;

    /**
     * Create a new repository instance.
     *
     * @param OperationCost $model
     */
    public function __construct(OperationCost $model = new OperationCost())
    {
        $this->model = $model;
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return OperationCost
     */
    public function create(array $data): OperationCost
    {
        return $this->model->create($data);
    }


    /**
     * Get all records.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find a record by ID.
     *
     * @param mixed $id
     * @return OperationCost|null
     */
    public function find($id): ?OperationCost
    {
        return $this->model->find($id);
    }

    /**
     * Select one record based on conditions.
     *
     * @param array $conditions
     * @return OperationCost|null
     */
    public function selectOne(array $conditions): ?OperationCost
    {
        return $this->model->where($conditions)->first();
    }

    /**
     * Update a record by ID.
     *
     * @param mixed $id
     * @param array $data
     * @return OperationCost|null
     */
    public function update($id, array $data):?OperationCost
    {
        $record = $this->find($id);
        if (!$record) {
            return null;
        }

        $record->update($data);
        return $record;
    }

    /**
     * Delete a record by ID.
     *
     * @param mixed $id
     * @return bool
     */
    public function delete($id): bool
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
     * @param string $operationType The type of operation to filter by.
     * @return MinMaxPriceDto Contains the minimum and maximum local prices for the specified country.
     */
    public function findPriceBandsByCountryCode(string $countryCode, string $operationType): MinMaxPriceDto
    {
        $minPrice = $this->model->whereCountryCode($countryCode)
            ->where('min_cost', '>', 0)
            ->where('operation_type', $operationType)
            ->min('min_cost');

        $maxPrice = $this->model->whereCountryCode($countryCode)
            ->where('operation_type', $operationType)
            ->max('max_cost');

        return new MinMaxPriceDto($minPrice, $maxPrice);
    }
}
