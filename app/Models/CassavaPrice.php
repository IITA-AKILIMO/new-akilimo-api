<?php

namespace App\Models;

use App\Models\Base\CassavaPrice as BaseCassavaPrice;

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereMaxLocalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereMaxUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereMinLocalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereMinUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice wherePriceActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaPrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CassavaPrice extends BaseCassavaPrice {}
