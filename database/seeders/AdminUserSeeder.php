<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = config('app.default_admin_password');

        if (blank($defaultPassword)) {
            throw new RuntimeException(
                'DEFAULT_ADMIN_PASSWORD is not set. Add it to your .env before seeding.'
            );
        }

        $user = User::updateOrCreate(
            ['username' => 'akilimo'],
            [
                'name' => 'Akilimo Admin',
                'email' => 'akilimo@cgiar.org',
                'password' => Hash::make($defaultPassword),
            ],
        );

        $this->command->info("Admin user [{$user->email}] ready.");

        // Skip key generation if the user already has an active key
        if ($user->apiKeys()->where('is_active', true)->exists()) {
            $this->command->warn('Active API key already exists — skipping key generation.');

            return;
        }

        $rawKey = 'ak_'.bin2hex(random_bytes(16));
        $prefix = substr($rawKey, 0, 12);

        $user->apiKeys()->create([
            'name' => 'Admin wildcard key',
            'key_prefix' => $prefix,
            'key_hash' => hash('sha256', $rawKey),
            'abilities' => null, // null = wildcard (*), grants all abilities
            'expires_at' => null, // never expires
        ]);

        $keyLine = "  X-Api-Key: {$rawKey}  ";
        $passLine = "  Password:  {$defaultPassword}  ";
        $width = max(mb_strlen($keyLine), mb_strlen($passLine));
        $pad = str_repeat('─', $width);

        $this->command->line('');
        $this->command->warn("┌{$pad}┐");
        $this->command->warn('│  COPY THESE CREDENTIALS — they will NOT be shown again.  │');
        $this->command->warn("│{$keyLine}│");
        $this->command->warn("│{$passLine}│");
        $this->command->warn("└{$pad}┘");
        $this->command->line('');
    }
}
