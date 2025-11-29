<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class MaizeData extends Data
{
    public string $produceType;
    public float $unitWeight;
    public float $unitPrice;
    public int $unitPriceMaize1;
    public int $unitPriceMaize2;
    public int $unitPricePotato1;
    public int $unitPricePotato2;
}
