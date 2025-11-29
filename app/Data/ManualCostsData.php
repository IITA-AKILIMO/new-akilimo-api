<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ManualCostsData extends Data
{
    public float $costManualPloughing;
    public float $costManualHarrowing;
    public float $costManualRidging;
}
