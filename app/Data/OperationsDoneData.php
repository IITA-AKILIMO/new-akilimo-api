<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class OperationsDoneData extends Data
{
    public bool $ploughingDone;
    public bool $harrowingDone;
    public bool $ridgingDone;
}
