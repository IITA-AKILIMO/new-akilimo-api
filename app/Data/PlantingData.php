<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class PlantingData extends Data
{
    public Carbon $plantingDate;
    public int $plantingDateWindow;
    public Carbon $harvestDate;
    public int $harvestDateWindow;
}
