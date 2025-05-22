<?php

namespace App\Models;

use App\Models\Base\UserFeedback as BaseUserFeedback;

/**
 * @property int $id
 * @property string $akilimo_usage
 * @property string $user_type
 * @property int $akilimo_rec_rating
 * @property int $akilimo_useful_rating
 * @property string|null $language
 * @property string|null $device_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereAkilimoRecRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereAkilimoUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereAkilimoUsefulRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFeedback whereUserType($value)
 *
 * @mixin \Eloquent
 */
class UserFeedback extends BaseUserFeedback
{
    protected $hidden = [
        'device_token',
    ];
}
