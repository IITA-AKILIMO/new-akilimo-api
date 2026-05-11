<?php

namespace App\Models;

use App\Enums\EnumUserRole;
use App\Models\Base\User as BaseUser;

class User extends BaseUser
{
    protected $casts = [
        'id' => 'int',
        'email_verified_at' => 'datetime',
        'role' => EnumUserRole::class,
    ];
}
