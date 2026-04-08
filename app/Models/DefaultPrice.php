<?php

namespace App\Models;

use App\Models\Base\DefaultPrice as BaseDefaultPrice;

class DefaultPrice extends BaseDefaultPrice
{
    public $incrementing = true;

    protected $fillable = [
        'country',
        'item',
        'price',
        'unit',
        'currency',
    ];
}
