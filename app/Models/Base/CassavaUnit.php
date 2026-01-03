<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CassavaUnit
 *
 * @property int $id
 * @property float $unit_weight
 * @property string $label
 * @property float $sort_order
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereUnitWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CassavaUnit whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CassavaUnit extends Model
{
    protected $table = 'cassava_units';

    protected $casts = [
        'unit_weight' => 'float',
        'sort_order' => 'float',
        'is_active' => 'bool',
    ];

    protected $fillable = [
        'unit_weight',
        'label',
        'sort_order',
        'description',
        'is_active',
    ];
}
