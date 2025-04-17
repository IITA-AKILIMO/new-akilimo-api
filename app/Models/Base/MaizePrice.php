<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MaizePrice
 *
 * @property int $id
 * @property string $country
 * @property float $min_local_price
 * @property float $max_local_price
 * @property float $min_usd
 * @property float $max_usd
 * @property bool $min_price
 * @property bool $max_price
 * @property bool|null $price_active
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $produce_type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereMaxLocalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereMaxUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereMinLocalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereMinUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice wherePriceActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereProduceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaizePrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MaizePrice extends Model
{
    protected $table = 'maize_prices';

    protected $casts = [
        'min_local_price' => 'float',
        'max_local_price' => 'float',
        'min_usd' => 'float',
        'max_usd' => 'float',
        'min_price' => 'bool',
        'max_price' => 'bool',
        'price_active' => 'bool',
        'sort_order' => 'int',
    ];
}
