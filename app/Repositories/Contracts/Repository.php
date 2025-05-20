<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


interface Repository
{
    public function all(): Collection;

    public function find(int|string $id): ?Model;

    public function selectOne(array $conditions): ?Model;

    public function create(array $data): Model;

    public function update(int|string $id, array $data): ?Model;

    public function delete(int|string $id): bool;
}
