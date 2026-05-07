<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Core\Models\BaseModel;
use Carbon\Carbon;

/**
 * Class Translation
 *
 * @property int $id
 * @property string $key
 * @property string $en
 * @property string|null $sw
 * @property string|null $rw
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereSw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Translation extends BaseModel
{
    protected $table = 'translations';

    public $incrementing = false;

    protected $casts = [
        'id' => 'int',
    ];

    protected $fillable = [
        'key',
        'en',
        'sw',
        'rw',
    ];
}
