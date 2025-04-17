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
 * @property string|null $country
 * @property string|null $fertilizer_key
 * @property float $min_price
 * @property float $max_price
 * @property float $price_per_bag
 * @property bool|null $price_active
 * @property int $sort_order
 * @property string|null $desc
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereFertilizerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice wherePriceActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice wherePricePerBag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FertilizerPrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FertilizerPrice extends Model
{
    protected $table = 'fertilizer_price';

    protected $casts = [
        'min_price' => 'float',
        'max_price' => 'float',
        'price_per_bag' => 'float',
        'price_active' => 'bool',
        'sort_order' => 'int',
    ];
}
