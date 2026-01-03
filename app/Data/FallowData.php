<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class FallowData extends Data
{
    public string $fallowType;
    public float $fallowHeight;
    public bool $fallowGreen;
}
