<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestmentAmount
 *
 * @property int $id
 * @property string|null $country
 * @property float $investment_amount
 * @property string|null $area_unit
 * @property bool|null $price_active
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
class InvestmentAmount extends Model
{
    protected $table = 'investment_amount';

    protected $casts = [
        'investment_amount' => 'float',
        'price_active' => 'bool',
        'sort_order' => 'int',
    ];
}
