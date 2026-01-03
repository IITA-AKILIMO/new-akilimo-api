<?php

namespace App\Models;

use App\Models\Base\UserFeedback as BaseUserFeedback;

class UserFeedback extends BaseUserFeedback
{
    protected $hidden = [
        'device_token',
    ];
}
