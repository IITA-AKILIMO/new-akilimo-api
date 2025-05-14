<?php

namespace App\Data;

use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\PaginatedDataCollection;

#[MapInputName(SnakeCaseMapper::class)]
class FertilizerData extends Data
{

    public string $name;

    #[MapInputName("fertilizer_type")]
    public string $type;

    public string $key;

    public float $weight;

    #[MapInputName("price")]
    public float $pricePerBag;

    #[MapInputName("selected")]
    public bool $available;
}
