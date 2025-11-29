<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class YieldInfoData extends Data
{
    public float $currentFieldYield;
    public float $currentMaizePerformance;
    public bool $sellToStarchFactory;
    public string $starchFactoryName;
}
