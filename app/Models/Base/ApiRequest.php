<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiRequest
 *
 * @property int $id
 * @property string $request_id
 * @property string|null $device_token
 * @property string|null $country_code
 * @property float|null $lat
 * @property float|null $lon
 * @property string|null $full_names
 * @property string|null $phone_number
 * @property string|null $gender
 * @property bool|null $fr
 * @property bool|null $ic
 * @property bool|null $pp
 * @property bool|null $sph
 * @property bool|null $spp
 * @property bool|null $excluded
 * @property array $droid_request
 * @property array $plumber_request
 * @property Carbon|null $request_started_at
 * @property int|null $request_duration_ms
 * @property array $plumber_response
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereDroidRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereExcluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereFullNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereIc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest wherePlumberRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest wherePlumberResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest wherePp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereRequestDurationMs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereRequestStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereSph($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereSpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ApiRequest extends Model
{
    protected $table = 'api_requests';

    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
        'fr' => 'bool',
        'ic' => 'bool',
        'pp' => 'bool',
        'sph' => 'bool',
        'spp' => 'bool',
        'excluded' => 'bool',
        'droid_request' => 'json',
        'plumber_request' => 'json',
        'request_started_at' => 'datetime',
        'request_duration_ms' => 'int',
        'plumber_response' => 'json',
    ];

    protected $fillable = [
        'request_id',
        'device_token',
        'country_code',
        'lat',
        'lon',
        'full_names',
        'phone_number',
        'gender',
        'fr',
        'ic',
        'pp',
        'sph',
        'spp',
        'excluded',
        'droid_request',
        'plumber_request',
        'request_started_at',
        'request_duration_ms',
        'plumber_response',
    ];
}
