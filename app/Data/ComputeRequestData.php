<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ComputeRequestData extends Data
{
    public string $areaUnit;
    public int $cassUpM1;
    public int $cassUpM2;
    public int $cassUpP1;
    public int $cassUpP2;
    public string $cassavaProduceType;
    public float $cassavaUnitPrice;
    public float $cassavaUnitWeight;
    public string $costLmoAreaBasis;
    public float $costManualHarrowing;
    public float $costManualPloughing;
    public float $costManualRidging;
    public float $costTractorHarrowing;
    public float $costTractorPloughing;
    public float $costTractorRidging;
    public float $costWeedingOne;
    public float $costWeedingTwo;

    public string $countryCode;
    public string $currencyCode;
    public float $currentFieldYield;
    public float $currentMaizePerformance;
    public bool $fallowGreen;
    public float $fallowHeight;
    public string $fallowType;
    public bool $fertilizerRec;
    public float $fieldSize;
    public bool $harrowingDone;

    public  \Illuminate\Support\Carbon $plantingDate;

    public int $plantingDateWindow;

    public \Illuminate\Support\Carbon $harvestDate;
    public int $harvestDateWindow;
    public string $interCroppedCrop;
    public bool $interCroppingMaizeRec;
    public bool $interCroppingPotatoRec;
    public string $maizeProduceType;
    public float $maizeUnitPrice;
    public float $maizeUnitWeight;
    public float $mapLat;
    public float $mapLong;
    public float $maxInvestment;
    public string $methodHarrowing;
    public string $methodPloughing;
    public string $methodRidging;
    public string $methodWeeding;
    public bool $plantingPracticesRec;
    public bool $ploughingDone;
    public bool $problemWeeds;
    public bool $ridgingDone;
    public int $riskAttitude;
    public bool $scheduledHarvestRec;
    public bool $scheduledPlantingRec;
    public bool $sellToStarchFactory;
    public string $starchFactoryName;
    public string $sweetPotatoProduceType;
    public float $sweetPotatoUnitPrice;
    public float $sweetPotatoUnitWeight;
    public bool $tractorHarrow;
    public bool $tractorPlough;
    public bool $tractorRidger;
    public string $useCase;
}
