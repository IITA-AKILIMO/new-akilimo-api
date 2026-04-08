<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['username' => 'akilimo'],
            [
                'name'     => 'Akilimo Admin',
                'email'    => 'admin@akilimo.org',
                'password' => Hash::make(config('app.key')), // change via password reset in production
            ],
        );

        // Skip key generation if the user already has an active wildcard key
        if ($user->apiKeys()->where('is_active', true)->exists()) {
            $this->command->warn('Admin user already has an active API key — skipping key generation.');
            return;
        }

        $rawKey = 'ak_' . bin2hex(random_bytes(16));
        $prefix = substr($rawKey, 0, 12);
        $hash   = hash('sha256', $rawKey);

        $user->apiKeys()->create([
            'name'       => 'Admin wildcard key',
            'key_prefix' => $prefix,
            'key_hash'   => $hash,
            'abilities'  => null, // null = wildcard (*), grants all abilities
            'expires_at' => null, // never expires
        ]);

        $this->command->info('Admin user seeded.');
        $this->command->line('');
        $this->command->warn('┌─────────────────────────────────────────────────────┐');
        $this->command->warn('│  COPY THIS KEY — it will NOT be shown again.        │');
        $this->command->warn('│                                                     │');
        $this->command->warn("│  X-Api-Key: {$rawKey}  │");
        $this->command->warn('└─────────────────────────────────────────────────────┘');
        $this->command->line('');
    }
}
