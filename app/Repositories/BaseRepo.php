<?php

namespace App\Repositories;

use App\Repositories\Contracts\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

/**
 * @template TModel of Model
 *
 * @implements Repository<TModel>
 */
abstract class BaseRepo implements Repository
{
    /**
     * @var TModel
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $model;

    protected array $logChannels;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->logChannels = config('logging.log_channels');
        $this->model = $this->getModelInstance();
    }

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
     * Get the model class name.
     *
     * @return class-string<TModel>
     */
    abstract protected function model(): string;

    /**
     * Get a new query builder for the model.
     */
    protected function query(array $with = []): Builder
    {
        return $this->model->newQuery()->with($with);
    }

    /**
     * Get all records.
     *
     * @return Collection<int, TModel>
     */
    public function all(array $with = []): Collection
    {
        return $this->query($with)->get();
    }

    /**
     * Find a record by ID.
     *
     * @return TModel
     */
    public function find(int|string $id, array $with = []): ?Model
    {
        return $this->query($with)->find($id);
    }

    public function findOrFail(int|string $id, array $with = []): ?Model
    {
        return $this->query($with)->findOrFail($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @return TModel
     */
    public function selectOneOrCreate(array $conditions, array $columns = ['*'], array $with = []): ?Model
    {
        return $this->query($with)
            ->where($conditions)
            ->select($columns)->firstOrCreate();
    }

    /**
     * Retrieve records matching conditions.
     *
     * @return Collection<int, TModel>
     */
    public function selectByCondition(array $conditions, array $columns = ['*'], array $with = []): Collection
    {
        return $this->query($with)
            ->where($conditions)
            ->select($columns)->get();
    }

    /**
     * Retrieve a single record matching condition.
     *
     * @return TModel
     */
    public function selectOne(array $conditions, array $columns = ['*'], array $with = []): ?Model
    {
        return $this->query($with)
            ->where($conditions)
            ->select($columns)->first();
    }

    /**
     * @return TModel
     */
    public function selectOneOrFail(array $conditions, array $columns = ['*'], array $with = []): ?Model
    {
        return $this->query($with)
            ->where($conditions)
            ->select($columns)->firstOrFail();
    }

    /**
     * Create a new record.
     *
     * @return TModel
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record.
     *
     * @return TModel
     */
    public function update(int|string $id, array $data): ?Model
    {
        $model = $this->query()->find($id);

        if ($model == null) {
            return null;
        }
        $model->update($data);

        return $model;
    }

    /**
     * Create or update a record by unique identifiers.
     *
     * @param array $input The full data array to insert or update.
     * @param array $identifiers Keys to match existing records (e.g. ['phone' => '123', 'campaign_id' => 5])
     * @return TModel
     *
     * @throws Throwable
     */
    public function createOrUpdate(array $input, array $identifiers): Model
    {
        return DB::transaction(function () use ($identifiers, $input) {
            /** @var TModel $model */
            $model = $this->model::updateOrCreate($identifiers, $input);

            return $model->fresh();
        });
    }

    /**
     * Delete a record.
     */
    public function delete(int|string $id): bool
    {
        return $this->query()->findOrFail($id)->delete();
    }

    public function existsWithConditions(
        array   $conditions = [],
        array   $whereIn = [],
        ?string $orderBy = null,
        string  $direction = 'desc',
    ): bool
    {
        $query = $this->query();

        // Apply standard where conditions
        if (!empty($conditions)) {
            $query->where($conditions);
        }

        // Apply whereIn conditions
        foreach ($whereIn as $column => $values) {
            if (!is_string($column) || !is_array($values)) {
                throw new InvalidArgumentException("whereIn must be ['column' => [values]]");
            }

            $query->whereIn($column, $values);
        }

        // Apply optional sorting
        if ($orderBy) {
            $query->orderBy($orderBy, $direction);
        }

        return $query->exists();
    }

    /**
     * Paginate with sorting and optional filters.
     */
    public function paginateWithSort(
        int    $perPage = 50,
        string $orderBy = 'created_at',
        string $direction = 'desc',
        array  $filters = [],
        array  $with = [],
    ): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = $this->query($with);

        if (!empty($filters)) {
            $query->where($filters);
        }

        return $query->orderBy($orderBy, $direction)->paginate($perPage);
    }
}
