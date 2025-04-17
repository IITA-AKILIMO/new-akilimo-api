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
 * @property float $min_usd
 * @property float $max_usd
 * @property float $min_ngn
 * @property float $max_ngn
 * @property float $max_tzs
 * @property float $min_tzs
 * @property bool|null $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMaxNgn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMaxTzs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMaxUsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMinNgn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMinTzs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationCost whereMinUsd($value)
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
        'min_usd' => 'float',
        'max_usd' => 'float',
        'min_ngn' => 'float',
        'max_ngn' => 'float',
        'max_tzs' => 'float',
        'min_tzs' => 'float',
        'active' => 'bool',
    ];
}
