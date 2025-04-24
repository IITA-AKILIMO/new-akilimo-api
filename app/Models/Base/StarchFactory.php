<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StarchFactory
 *
 * @property int $id
 * @property string $factory_name
 * @property string $factory_label
 * @property string $country
 * @property bool|null $factory_active
 * @property int|null $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereFactoryActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereFactoryLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereFactoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StarchFactory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StarchFactory extends Model
{
    protected $table = 'starch_factories';

    protected $casts = [
        'factory_active' => 'bool',
        'sort_order' => 'int',
    ];

    protected $fillable = [
        'factory_name',
        'factory_label',
        'country',
        'factory_active',
        'sort_order',
    ];
}
