<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class FarmLocationData extends Data
{
    public string $countryCode;
    public string $useCase;
    public float $mapLat;
    public float $mapLong;
}
