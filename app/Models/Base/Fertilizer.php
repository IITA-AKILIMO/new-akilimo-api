<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fertilizer
 *
 * @property int $id
 * @property string|null $fertilizer_label
 * @property string $name
 * @property string $type
 * @property string|null $fertilizer_key
 * @property int $weight
 * @property string $country
 * @property int|null $sort_order
 * @property string $use_case
 * @property bool|null $available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereFertilizerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fertilizer whereFertilizerLabel($value)
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
class Fertilizer extends Model
{
    protected $table = 'fertilizers';

    protected $columns = [
        'id',
        'fertilizer_label',
        'name',
        'type',
        'fertilizer_key',
        'weight',
        'country',
        'sort_order',
        'use_case',
        'available',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'weight' => 'int',
        'sort_order' => 'int',
        'available' => 'bool',
    ];

    protected $fillable = [
        'fertilizer_label',
        'name',
        'type',
        'fertilizer_key',
        'weight',
        'country',
        'sort_order',
        'use_case',
        'available',
    ];
}
