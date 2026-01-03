<?php

namespace App\Data;

class MinMaxPriceDto
{
    public float $minPrice = 0.0;
    public float $maxPrice = 0.0;

    /**
     * @param float $minLocalPrice
     * @param float $maxLocalPrice
     */
    public function __construct(float $minLocalPrice=0.0, float $maxLocalPrice=0.0)
    {
        $this->minPrice = $minLocalPrice;
        $this->maxPrice = $maxLocalPrice;
    }
}
