<?php

namespace App\Models;

use App\Models\Base\VAppRequestStatsView as BaseVAppRequestStatsView;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $request_date
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
class VAppRequestStatsView extends BaseVAppRequestStatsView
{
    protected $hidden = [
        'device_token',
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
