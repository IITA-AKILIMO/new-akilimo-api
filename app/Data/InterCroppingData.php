<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class InterCroppingData extends Data
{
    public string $interCroppedCrop;
    public bool $interCroppingMaizeRec;
    public bool $interCroppingPotatoRec;
}
