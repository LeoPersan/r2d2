<?php

namespace Leopersan\R2d2\Commands;

use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/request.stub');
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
        return $rootNamespace.'\Http\Requests';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $fields = collect(explode(',', $this->option('fields')))->map(fn ($field) => explode(':', $field));
        $types = $fields->map(fn ($field) => $field[1] ?? 'string');
        $fields = $fields->map(fn ($field) => $field[0]);
        $rules = collect($fields)->map(fn ($field) => "'{$field}' => 'required|string',")->join("\n\t\t\t");

        return str_replace('{{ rules }}',$rules,parent::replaceClass($stub, $name));
    }

    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_REQUIRED, 'The name fields separeted with comma'],
        ];
    }
}
