<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PotatoPrice
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
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereMaxLocalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereMaxUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereMinLocalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereMinUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice wherePriceActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PotatoPrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PotatoPrice extends Model
{
    protected $table = 'potato_prices';

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
