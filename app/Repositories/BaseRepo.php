<?php

namespace App\Repositories;

use App\Repositories\Contracts\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @implements Repository<TModel>
 */
abstract class BaseRepo implements Repository
{
    /**
     * @var TModel
     */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->getModelInstance();
    }

    /**
     * Get the model class name.
     *
     * @return class-string<TModel>
     */
    abstract protected function model(): string;

    /**
     * Instantiate the model.
     *
     * @return TModel
     */
    protected function getModelInstance()
    {
        return app($this->model());
    }

    /**
     * Get a new query builder for the model.
     *
     * @return Builder|TModel
     */
    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Get all records.
     *
     * @return Collection<int, TModel>
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * Find a record by ID.
     *
     * @param int|string $id
     * @return TModel|null
     */
    public function find(int|string $id): ?Model
    {
        return $this->query()->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int|string $id
     * @return TModel
     */
    public function findOrFail(int|string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Retrieve records matching conditions.
     *
     * @param array $conditions
     * @param array $columns
     * @return Collection<int, TModel>
     */
    public function selectByCondition(array $conditions, array $columns = ['*']): Collection
    {
        return $this->query()
            ->where($conditions)
            ->select($columns)->get();
    }

    /**
     * Retrieve a single record matching conditions.
     *
     * @param array $conditions
     * @param array $columns
     * @return Model|null
     */
    public function selectOne(array $conditions, array $columns = ['*']): ?Model
    {
        return $this->query()
            ->where($conditions)
            ->select($columns)->first();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return TModel
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record.
     *
     * @param int|string $id
     * @param array $data
     * @return Model
     */
    public function update(int|string $id, array $data): Model
    {
        $model = $this->query()->findOrFail($id);

        $model->update($data);

        return $model;
    }

    /**
     * Delete a record.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        return $this->query()->findOrFail($id)->delete();
    }

    /**
     * Paginate with sorting and optional filters.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $direction
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginateWithSort(
        int    $perPage = 50,
        string $sortBy = 'created_at',
        string $direction = 'desc',
        array  $filters = []
    ): LengthAwarePaginator
    {
        $query = $this->query();

        if (!empty($filters)) {
            $query->where($filters);
        }

        return $query->orderBy($sortBy, $direction)->paginate($perPage);
    }
}
