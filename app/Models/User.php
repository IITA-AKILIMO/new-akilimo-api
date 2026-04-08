<?php

namespace App\Models;

use App\Models\Base\User as BaseUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends BaseUser implements AuthenticatableContract
{
    use Authenticatable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }
}
