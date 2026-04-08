<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $key_prefix
 * @property string $key_hash
 * @property array|null $abilities
 * @property bool $is_active
 * @property Carbon|null $last_used_at
 * @property Carbon|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ApiKey extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'key_prefix',
        'key_hash',
        'abilities',
        'is_active',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'abilities' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isUsable(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    /**
     * Check whether this key grants the given ability.
     * A null abilities list or a ['*'] entry grants everything.
     */
    public function can(string $ability): bool
    {
        $abilities = $this->abilities ?? ['*'];

        return in_array('*', $abilities, true) || in_array($ability, $abilities, true);
    }
}
