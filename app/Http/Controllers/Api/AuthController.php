<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Authenticate with username or email + password.
     * Returns a bearer token valid for the duration set in AUTH_TOKEN_TTL_DAYS (default: 30 days).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $login = $request->input('login');

        $user = User::where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if ($user === null || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $ttlDays   = (int) config('auth.token_ttl_days', 30);
        $expiresAt = now()->addDays($ttlDays);
        $rawToken  = bin2hex(random_bytes(32)); // 64 hex chars

        PersonalAccessToken::create([
            'tokenable_type' => User::class,
            'tokenable_id'   => $user->id,
            'name'           => 'login',
            'token'          => hash('sha256', $rawToken),
            'abilities'      => ['*'],
            'expires_at'     => $expiresAt,
        ]);

        return response()->json([
            'token_type' => 'Bearer',
            'token'      => $rawToken,
            'expires_at' => $expiresAt->toIso8601String(),
            'user'       => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
            ],
        ]);
    }

    /**
     * Revoke the token used to make this request.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->attributes->get('auth_token');

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
