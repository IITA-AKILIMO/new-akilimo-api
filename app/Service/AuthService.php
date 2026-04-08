<?php

namespace App\Service;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt to authenticate a user by username/email and password.
     *
     * Returns the raw token string on success, or null on failure.
     * The token is hashed before storage and never retrievable again.
     *
     * @return array{user: User, token: string, expires_at: \Carbon\Carbon}|null
     */
    public function attempt(string $username, string $password): ?array
    {
        $user = User::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if ($user === null || !Hash::check($password, $user->password)) {
            return null;
        }

        $ttlDays   = (int) config('auth.token_ttl_days', 30);
        $expiresAt = now()->addDays($ttlDays);
        $rawToken  = bin2hex(random_bytes(32));

        PersonalAccessToken::create([
            'tokenable_type' => User::class,
            'tokenable_id'   => $user->id,
            'name'           => 'login',
            'token'          => hash('sha256', $rawToken),
            'abilities'      => ['*'],
            'expires_at'     => $expiresAt,
        ]);

        return [
            'user'       => $user,
            'token'      => $rawToken,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Revoke a specific personal access token.
     */
    public function revoke(PersonalAccessToken $token): void
    {
        $token->delete();
    }
}
