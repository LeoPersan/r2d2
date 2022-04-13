<?php

namespace Leopersan\R2d2\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected $uses = [];
    protected $arquivos = [];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        if ($this->option('all')) {
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('policy', true);
            $this->input->setOption('resource', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }

        if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
            $this->createController();
        }

        if ($this->option('policy')) {
            $this->createPolicy();
        }
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly($this->argument('name'));

        $this->call('make:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--fields' => $this->option('fields'),
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:seeder', [
            'name' => "{$seeder}Seeder",
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--fields' => $this->option('fields'),
        ]));
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createPolicy()
    {
        $policy = Str::studly(class_basename($this->argument('name')));

        $this->call('make:policy', [
            'name' => "{$policy}Policy",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('pivot')
            ? $this->resolveStubPath('/stubs/model.pivot.stub')
            : $this->resolveStubPath('/stubs/model.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Models';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, policy, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['fields', null, InputOption::VALUE_REQUIRED, 'The name fields separeted with comma'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a new policy for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Create new form request classes and use them in the resource controller'],
        ];
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $plural = Str::plural(lcfirst(class_basename($class)));
        $fields = collect(explode(',', $this->option('fields')))->map(fn ($field) => explode(':', $field));
        $types = $fields->map(fn ($field) => $field[1] ?? 'string');
        $fields = $fields->map(fn ($field) => $field[0]);
        $fillable = "'".implode("', '", $fields->toArray())."'";
        $casts = $this->getCasts($fields, $types);
        $uses = collect($this->uses)->unique()->map(fn ($use) => "use {$use};")->implode("\n");

        return str_replace(
            [
                'DummyCasts', '{{ casts }}', '{{casts}}',
                'DummyUses', '{{ uses }}', '{{uses}}',
                'DummyPluralModelVariable', '{{ pluralModelVariable }}', '{{pluralModelVariable}}',
                'DummyFillable', '{{ fillable }}', '{{fillable}}',
            ],
            [
                $casts, $casts, $casts,
                $uses, $uses, $uses,
                $plural, $plural, $plural,
                $fillable, $fillable, $fillable,
            ],
            parent::replaceClass($stub, $name)
        );
    }

    public function getCasts(Collection $fields, Collection $types): string
    {
        $casts = $types->filter(fn ($type, $index) => method_exists($this, 'getCast'.Str::studly($type)))
                        ->map(fn ($type, $index) => $this->{'getCast'.Str::studly($type)}($fields[$index]));
        if ($this->arquivos) {
            $paths = collect($this->arquivos)->map(
                fn ($field) => "\t\t'{$field}' => '{{ pluralModelVariable }}/{$field}',"
            );
            $casts = $casts->merge(["\t];", "\tpublic \$paths = ["])->merge($paths);
        }
        return ltrim(implode("\n", $casts->toArray()));
    }

    public function getCastArray(string $field): string
    {
        return "\t\t'{$field}' => 'array',";
    }

    public function getCastJson(string $field): string
    {
        return "\t\t'{$field}' => 'json',";
    }

    public function getCastBoolean(string $field): string
    {
        return "\t\t'{$field}' => 'boolean',";
    }

    public function getCastCnpj($field)
    {
        $this->uses[] = 'App\Casts\Cnpj';
        return "\t\t'{$field}' => Cnpj::class,";
    }

    public function getCastCpf($field)
    {
        $this->uses[] = 'App\Casts\Cpf';
        return "\t\t'{$field}' => Cpf::class,";
    }

    public function getRuleCpfCnpj($field)
    {
        $this->uses[] = 'App\Casts\CpfCnpj';
        return "\t\t'{$field}' => CpfCnpj::class,";
    }

    public function getCastPdf($field)
    {
        $this->uses[] = 'App\Casts\Arquivo';
        $this->arquivos[] = $field;
        return "\t\t'{$field}' => Arquivo::class,";
    }

    public function getCastImagem($field)
    {
        $this->uses[] = 'App\Casts\Arquivo';
        $this->arquivos[] = $field;
        return "\t\t'{$field}' => Arquivo::class,";
    }

    public function getCastTelefone($field)
    {
        $this->uses[] = 'App\Casts\Telefone';
        return "\t\t'{$field}' => Telefone::class,";
    }
}
