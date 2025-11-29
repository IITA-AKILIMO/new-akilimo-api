<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class TractorCostsData extends Data
{
    public bool $tractorPlough;
    public bool $tractorHarrow;
    public bool $tractorRidger;
    public string $costLmoAreaBasis;
    public float $costTractorPloughing;
    public float $costTractorHarrowing;
    public float $costTractorRidging;
}
