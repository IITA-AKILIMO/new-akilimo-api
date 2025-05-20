<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OperationCost
 *
 * @property int $id
 * @property string $operation_name
 * @property string $operation_type
 * @property string $country_code
 * @property float $min_cost
 * @property float $max_cost
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMaxCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMinCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereOperationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class OperationCost extends Model
{
    protected $table = 'operation_costs';

    protected $casts = [
        'min_cost' => 'float',
        'max_cost' => 'float',
        'is_active' => 'bool',
    ];

    protected $fillable = [
        'operation_name',
        'operation_type',
        'country_code',
        'min_cost',
        'max_cost',
        'is_active',
    ];
}
