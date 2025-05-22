<?php

namespace App\Models;

use App\Models\Base\StarchFactory as BaseStarchFactory;

/**
 * @property int $id
 * @property string $factory_name
 * @property string $factory_label
 * @property string $country
 * @property bool|null $factory_active
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
class StarchFactory extends BaseStarchFactory {}
