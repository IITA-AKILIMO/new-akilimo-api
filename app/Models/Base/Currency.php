<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 *
 * @property int $id
 * @property string|null $country_code
 * @property string|null $country
 * @property string|null $currency_name
 * @property string|null $currency_code
 * @property string|null $currency_symbol
 * @property string|null $currency_native_symbol
 * @property string|null $name_plural
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrencyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrencyNativeSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereNamePlural($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Currency whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
        'country_code',
        'country',
        'currency_name',
        'currency_code',
        'currency_symbol',
        'currency_native_symbol',
        'name_plural',
    ];
}
