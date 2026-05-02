<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DefaultPrice
 *
 * @property int $id
 * @property string $country
 * @property string $item
 * @property float $price
 * @property string $unit
 * @property string|null $currency
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DefaultPrice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DefaultPrice extends Model
{
    protected $table = 'default_prices';

    protected $casts = [
        'price' => 'float',
    ];

    protected $fillable = [
        'country',
        'item',
        'price',
        'unit',
        'currency',
    ];
}
