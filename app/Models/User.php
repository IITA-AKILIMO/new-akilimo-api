<?php

namespace App\Models;

use App\Models\Base\User as BaseUser;

class User extends BaseUser
{
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
