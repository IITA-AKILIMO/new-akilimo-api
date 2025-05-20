<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 *
 * @property string|null $COUNTRY
 * @property string|null $COUNTRY_CODE
 * @property string|null $CURRENCY_CODE
 * @property string|null $NAME_OF_CURRENCY
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCOUNTRY($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCOUNTRYCODE($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCURRENCYCODE($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereNAMEOFCURRENCY($value)
 *
 * @mixin \Eloquent
 */
class Country extends Model
{
    protected $table = 'countries';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'COUNTRY',
        'COUNTRY_CODE',
        'CURRENCY_CODE',
        'NAME_OF_CURRENCY',
    ];
}
