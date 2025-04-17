<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    protected $signature = 'make:repo {name}';

    protected $description = 'Create a new repository class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $repositoryPath = app_path("Repositories/{$name}.php");

        if (file_exists($repositoryPath)) {
            $this->error("Repository '{$name}' already exists!");

            return false;
        }

        // Ensure the directory exists
        (new Filesystem)->ensureDirectoryExists(app_path('Repositories'));

        // Define repository template
        $stub = <<<PHP
        <?php

        namespace App\Repositories;

        class {$name}
        {
            public function all()
            {
                // TODO Implement logic
            }

            public function find(\$id)
            {
                // TODO Implement logic
            }
        }
        PHP;

        // Create the file
        file_put_contents($repositoryPath, $stub);

        $this->info("Repository '{$name}' created successfully.");
    }
}
