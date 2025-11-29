<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class YieldInfoData extends Data
{
    public float $currentFieldYield;
    public float $currentMaizePerformance;
    public bool $sellToStarchFactory;
    public string $starchFactoryName;
}
