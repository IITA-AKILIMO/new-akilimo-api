<?php

namespace App\Http\Controllers\Web;

use App\Auth\TokenAbility;
use App\Models\ApiKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PlaygroundTokenController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $keys = ApiKey::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'key_prefix', 'is_active', 'abilities', 'last_used_at', 'expires_at', 'created_at']);

        return response()->json($keys->map(fn ($k) => [
            'id' => $k->id,
            'name' => $k->name,
            'key_prefix' => $k->key_prefix,
            'is_active' => $k->is_active,
            'abilities' => $k->abilities ?? ['*'],
            'last_used_at' => $k->last_used_at?->toIso8601String(),
            'expires_at' => $k->expires_at?->toIso8601String(),
            'created_at' => $k->created_at?->toIso8601String(),
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $user = $request->user();

        $abilities = match ($user->role ?? 'playground') {
            'admin' => [TokenAbility::WILDCARD],
            'partner' => TokenAbility::PARTNER_ABILITIES,
            default => TokenAbility::PLAYGROUND_ABILITIES,
        };

        $rawKey = 'ak_'.bin2hex(random_bytes(16));
        $prefix = substr($rawKey, 0, 12);
        $hash = hash('sha256', $rawKey);

        $key = ApiKey::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'key_prefix' => $prefix,
            'key_hash' => $hash,
            'abilities' => $abilities,
            'is_active' => true,
        ]);

        return response()->json([
            'id' => $key->id,
            'name' => $key->name,
            'key' => $rawKey,
            'key_prefix' => $prefix,
            'is_active' => true,
            'abilities' => $key->abilities,
            'created_at' => $key->created_at?->toIso8601String(),
        ], 201);
    }

    public function revoke(Request $request, int $id): JsonResponse
    {
        $key = ApiKey::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $key->update(['is_active' => false]);

        return response()->json(['message' => 'API key revoked.']);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $key = ApiKey::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $key->delete();

        return response()->json(['message' => 'API key deleted.']);
    }
}
