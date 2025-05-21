<?php

namespace App\Repositories;

use App\Repositories\Contracts\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
abstract class BaseRepository implements Repository
{
    /**
     * @var TModel
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->getModelInstance();
    }

    /**
     * @return class-string<TModel>
     */
    abstract protected function model(): string;

    /**
     * Instantiate the model.
     */
    protected function getModelInstance(): Model
    {
        return app($this->model());
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Retrieves records based on the specified conditions.
     *
     * @param  array  $conditions  An associative array of conditions for filtering the records.
     * @param  array  $columns  Optional array of columns to select, with support for aliases.
     *                          Examples:
     *                          - ['name', 'email']
     *                          - ['name as username', 'email as user_email']
     * @return Collection A collection of retrieved records.
     */
    public function selectByCondition(array $conditions, array $columns = ['*']): Collection
    {
        return $this->model->where($conditions)->select($columns)->get();
    }

    /**
     * Retrieve a single record matching the given conditions.
     *
     * @param  array  $conditions  Conditions to filter the query.
     * @param  array  $columns  Columns to select in the query. Defaults to all columns.
     * @return Model|null The matching model instance or null if no match is found.
     */
    public function selectOne(array $conditions, array $columns = ['*']): ?Model
    {
        return $this->model->where($conditions)->select($columns)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): ?Model
    {
        $model = $this->find($id);

        if ($model) {
            $model->update($data);
        }

        return $model;
    }

    public function delete(int|string $id): bool
    {
        $model = $this->find($id);

        return $model ? $model->delete() : false;
    }

    public function paginateWithSort(
        int $perPage = 50,
        string $sortBy = 'created_at',
        string $direction = 'desc',
        array $filters = []
    ): LengthAwarePaginator {
        $query = $this->model->newQuery();

        if (! empty($filters)) {
            $query->where($filters);
        }

        return $query->orderBy($sortBy, $direction)->paginate($perPage);
    }
}
