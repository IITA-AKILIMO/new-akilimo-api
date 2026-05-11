<?php

/**
 * (c) 2026 Munywele Consulting LTD — https://munywele.co.ke
 *
 * For license information, see the LICENSE file.
 */

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

abstract class BaseUser extends AuthFactory
{
    use HasApiTokens, HasFactory, Notifiable;
}
