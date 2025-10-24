<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FertilizerPrice
 *
 * @property int $id
 * @property float $min_usd
 * @property float $max_usd
 * @property float $price_per_bag
 * @property bool|null $price_active
 * @property int $sort_order
 * @property string|null $desc
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $country
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereMaxUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereMinUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice wherePriceActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice wherePricePerBag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FertilizerPrice extends Model
{
    protected $table = 'fertilizer_prices';

    protected $columns = [
        'id',
        'min_usd',
        'max_usd',
        'price_per_bag',
        'price_active',
        'sort_order',
        'desc',
        'created_at',
        'updated_at',
        'country',
    ];

    protected $casts = [
        'min_usd' => 'float',
        'max_usd' => 'float',
        'price_per_bag' => 'float',
        'price_active' => 'bool',
        'sort_order' => 'int',
    ];

    protected $fillable = [
        'min_usd',
        'max_usd',
        'price_per_bag',
        'price_active',
        'sort_order',
        'desc',
        'country',
    ];
}
