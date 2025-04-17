<?php

namespace App\Models;

use App\Models\Base\InvestmentAmount as BaseInvestmentAmount;

/**
 * @property int $id
 * @property string|null $country
 * @property float $investment_amount
 * @property string|null $area_unit
 * @property bool|null $price_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereAreaUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereInvestmentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount wherePriceActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentAmount whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class InvestmentAmount extends BaseInvestmentAmount
{
    protected $fillable = [
        'country',
        'investment_amount',
        'area_unit',
        'price_active',
        'sort_order',
    ];
}
