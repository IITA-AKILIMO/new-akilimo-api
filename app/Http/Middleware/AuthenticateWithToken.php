<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authenticates requests via one of two mechanisms (tried in order):
 *
 *  1. Bearer token  — Authorization: Bearer <token>
 *     Looked up in personal_access_tokens (SHA-256 hash match).
 *
 *  2. API key       — X-Api-Key: <key>
 *     Looked up in api_keys (SHA-256 hash match).
 *     Must be active and not expired.
 *
 * On success the resolved User is bound into the request so downstream
 * code can call $request->user().
 */
class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->resolveFromBearer($request)
            ?? $this->resolveFromApiKey($request);

        if ($user === null) {
            return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    private function resolveFromBearer(Request $request): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $raw = $request->bearerToken();

        if (blank($raw)) {
            return null;
        }

        $hash  = hash('sha256', $raw);
        $token = PersonalAccessToken::where('token', $hash)
            ->with('tokenable')
            ->first();

        if ($token === null) {
            return null;
        }

        if ($token->expires_at !== null && $token->expires_at->isPast()) {
            return null;
        }

        $token->forceFill(['last_used_at' => now()])->saveQuietly();

        return $token->tokenable;
    }

    private function resolveFromApiKey(Request $request): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $raw = $request->header('X-Api-Key');

        if (blank($raw)) {
            return null;
        }

        $hash   = hash('sha256', $raw);
        $apiKey = ApiKey::where('key_hash', $hash)
            ->with('user')
            ->first();

        if ($apiKey === null || !$apiKey->isUsable()) {
            return null;
        }

        $apiKey->forceFill(['last_used_at' => now()])->saveQuietly();

        return $apiKey->user;
    }
}
