<?php

namespace App\Models;

use App\Models\Base\Fertilizer as BaseFertilizer;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $fertilizer_key
 * @property int $weight
 * @property string $country
 * @property int|null $sort_order
 * @property string $use_case
 * @property bool|null $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereFertilizerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereUseCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereWeight($value)
 *
 * @mixin \Eloquent
 */
class Fertilizer extends BaseFertilizer {}
