<?php

namespace App\Models;

use AngelSourceLabs\LaravelSpatial\Eloquent\SpatialTrait;
use App\Models\Base\Country as BaseCountry;

class Country extends BaseCountry
{
    use SpatialTrait;

    protected array $spatialFields = ['boundary'];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'int',
        'latitude' => 'float',
        'longitude' => 'float',
        'min_latitude' => 'float',
        'max_latitude' => 'float',
        'min_longitude' => 'float',
        'max_longitude' => 'float',
    ];
}
