<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProducePrice
 *
 * @property int $id
 * @property string $country
 * @property string $produce_name
 * @property float $min_price
 * @property float $max_price
 * @property bool $is_active
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereMaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereProduceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProducePrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ProducePrice extends Model
{
    protected $table = 'produce_prices';

    protected $casts = [
        'min_price' => 'float',
        'max_price' => 'float',
        'is_active' => 'bool',
        'sort_order' => 'int',
    ];

    protected $fillable = [
        'country',
        'produce_name',
        'min_price',
        'max_price',
        'is_active',
        'sort_order',
    ];
}
