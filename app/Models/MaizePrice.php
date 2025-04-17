<?php

namespace App\Models;

use App\Models\Base\MaizePrice as BaseMaizePrice;

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
class MaizePrice extends BaseMaizePrice
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
        'produce_type',
    ];
}
