<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestFertilizer
 *
 * @property int $fertilizer_id
 * @property int|null $request_id
 * @property string $fertilizer_type
 * @property bool|null $available
 * @property float $price
 * @property float $weight
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer whereFertilizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer whereFertilizerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFertilizer whereWeight($value)
 *
 * @mixin \Eloquent
 */
class RequestFertilizer extends Model
{
    protected $table = 'request_fertilizer';

    protected $primaryKey = 'fertilizer_id';

    public $timestamps = false;

    protected $casts = [
        'request_id' => 'int',
        'available' => 'bool',
        'price' => 'float',
        'weight' => 'float',
    ];
}
