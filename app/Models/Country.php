<?php

namespace App\Models;

use App\Models\Base\Country as BaseCountry;

/**
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
class Country extends BaseCountry
{
    protected $fillable = [
        'COUNTRY',
        'COUNTRY_CODE',
        'CURRENCY_CODE',
        'NAME_OF_CURRENCY',
    ];
}
