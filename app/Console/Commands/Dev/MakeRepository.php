<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    protected $signature = 'make:repo {name} {--model=}';

    protected $description = 'Create a new repository class';

    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        $repositoryPath = app_path("Repositories/{$name}.php");

        if (file_exists($repositoryPath)) {
            $this->error("Repository '{$name}' already exists!");

            return false;
        }

        (new Filesystem)->ensureDirectoryExists(app_path('Repositories'));

        $modelImport = $model ? "use App\\Models\\{$model};" : '';
        $modelVariable = $model ? lcfirst(class_basename($model)) : 'model';
        $modelType = $model ?: 'Model';

        $stub = <<<PHP
<?php

namespace App\Repositories;

$modelImport

class {$name}
{
    protected \${$modelVariable};

    public function __construct({$modelType} \${$modelVariable})
    {
        \$this->{$modelVariable} = \${$modelVariable};
    }

    public function all()
    {
        return \$this->{$modelVariable}->all();
    }

    public function find(\$id)
    {
        return \$this->{$modelVariable}->find(\$id);
    }

    public function selectOne(array \$conditions)
    {
        return \$this->{$modelVariable}->where(\$conditions)->first();
    }

    public function update(\$id, array \$data)
    {
        \$record = \$this->find(\$id);
        if (!\$record) {
            return null;
        }

        \$record->update(\$data);
        return \$record;
    }

    public function delete(\$id)
    {
        \$record = \$this->find(\$id);
        if (!\$record) {
            return false;
        }

        return \$record->delete();
    }
}
PHP;

        file_put_contents($repositoryPath, $stub);

        $this->info("Repository '{$name}' created successfully.");
        return true;
    }
}
