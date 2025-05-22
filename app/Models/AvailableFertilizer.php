<?php

namespace App\Models;

use App\Models\Base\AvailableFertilizer as BaseAvailableFertilizer;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $n_content
 * @property int $p_content
 * @property int $k_content
 * @property int $weight
 * @property float $price
 * @property string $country
 * @property string|null $use_case
 * @property bool|null $available
 * @property bool|null $custom
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereKContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereNContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer wherePContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereUseCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvailableFertilizer whereWeight($value)
 *
 * @mixin \Eloquent
 */
class AvailableFertilizer extends BaseAvailableFertilizer {}
