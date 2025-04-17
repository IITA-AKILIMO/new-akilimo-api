<?php

namespace App\Models;

use App\Models\Base\PotatoPrice as BasePotatoPrice;

/**
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
class PotatoPrice extends BasePotatoPrice
{
    protected $fillable = [
        'country',
        'min_local_price',
        'max_local_price',
        'min_usd',
        'max_usd',
        'min_price',
        'max_price',
        'price_active',
        'sort_order',
    ];
}
