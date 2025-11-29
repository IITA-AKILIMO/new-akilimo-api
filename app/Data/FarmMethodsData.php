<?php

namespace App\Data;

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class FarmMethodsData extends Data
{
    public string $methodPloughing;
    public string $methodHarrowing;
    public string $methodRidging;
    public string $methodWeeding;
}
