<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AvailableFertilizer
 *
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
class AvailableFertilizer extends Model
{
    protected $table = 'available_fertilizer';

    protected $columns = [
        'id',
        'name',
        'type',
        'n_content',
        'p_content',
        'k_content',
        'weight',
        'price',
        'country',
        'use_case',
        'available',
        'custom',
        'sort_order',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'n_content' => 'int',
        'p_content' => 'int',
        'k_content' => 'int',
        'weight' => 'int',
        'price' => 'float',
        'available' => 'bool',
        'custom' => 'bool',
        'sort_order' => 'int',
    ];

    protected $fillable = [
        'name',
        'type',
        'n_content',
        'p_content',
        'k_content',
        'weight',
        'price',
        'country',
        'use_case',
        'available',
        'custom',
        'sort_order',
    ];
}
