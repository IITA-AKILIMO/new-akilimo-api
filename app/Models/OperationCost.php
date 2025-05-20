<?php

namespace App\Models;

use App\Models\Base\OperationCost as BaseOperationCost;

/**
 * @property int $id
 * @property string $operation_name
 * @property string $operation_type
 * @property string $country_code
 * @property float $min_cost
 * @property float $max_cost
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
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
class OperationCost extends BaseOperationCost {}
