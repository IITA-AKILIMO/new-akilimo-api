<?php

namespace App\Data;

class MinMaxPriceDto
{
    public float $min_price = 0.0;
    public float $max_price = 0.0;

    /**
     * @param float $minLocalPrice
     * @param float $maxLocalPrice
     */
    public function __construct(float $minLocalPrice, float $maxLocalPrice)
    {
        $this->min_price = $minLocalPrice;
        $this->max_price = $maxLocalPrice;
    }
}
