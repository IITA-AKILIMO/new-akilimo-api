<?php

namespace App\Console\Commands\Disabled;

use Illuminate\Database\Console\WipeCommand;
use Illuminate\Support\Facades\Artisan;

class DisableWipeCommand extends WipeCommand
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->error("The {$this->name}  command has been disabled");

        $this->info('Available database commands you can use instead:');

        $commands = collect(Artisan::all())->filter(function ($command, $name) {
            return str_starts_with($name, 'db:');
        })->keys()->sort();

        $this->table(['BaseCommand'], $commands->map(function ($command) {
            return [$command];
        })->toArray());

        $this->line('');
        $this->line('You can also use database GUI tools or connect directly via command line clients.');

        return 1;
    }
}
