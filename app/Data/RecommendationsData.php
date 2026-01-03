<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class RecommendationsData extends Data
{
    public bool $fertilizerRec;
    public bool $plantingPracticesRec;
    public bool $scheduledPlantingRec;
    public bool $scheduledHarvestRec;
}
