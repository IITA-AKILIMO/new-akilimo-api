<?php

namespace App\Models;

use App\Models\Base\User as BaseUser;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends BaseUser
{
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }
}
