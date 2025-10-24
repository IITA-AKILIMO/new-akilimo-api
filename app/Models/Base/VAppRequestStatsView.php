<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VAppRequestStatsView
 *
 * @property int $id
 * @property Carbon|null $request_date
 * @property string|null $device_token
 * @property string|null $country_code
 * @property float|null $lat
 * @property float|null $lon
 * @property string|null $full_names
 * @property string|null $gender_name
 * @property bool|null $excluded
 * @property string|null $gender
 * @property string|null $phone_number
 * @property string $user_type
 * @property string|null $use_case
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereExcluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereFullNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereGenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereUseCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VAppRequestStatsView whereUserType($value)
 *
 * @mixin \Eloquent
 */
class VAppRequestStatsView extends Model
{
    protected $table = 'v_app_request_stats_view';

    public $incrementing = false;

    protected $columns = [
        'id',
        'request_date',
        'device_token',
        'country_code',
        'lat',
        'lon',
        'full_names',
        'gender_name',
        'excluded',
        'gender',
        'phone_number',
        'user_type',
        'use_case',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'int',
        'request_date' => 'datetime',
        'lat' => 'float',
        'lon' => 'float',
        'excluded' => 'bool',
    ];

    protected $fillable = [
        'id',
        'request_date',
        'device_token',
        'country_code',
        'lat',
        'lon',
        'full_names',
        'gender_name',
        'excluded',
        'gender',
        'phone_number',
        'user_type',
        'use_case',
    ];
}
