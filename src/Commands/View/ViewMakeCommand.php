<?php

namespace Leopersan\R2d2\Commands\View;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;
use Leopersan\R2d2\Commands\GeneratorCommand;
use Leopersan\R2d2\Traits\ParseFields;
use Leopersan\R2d2\Types\InterfaceType;

class ViewMakeCommand extends GeneratorCommand
{
    use ParseFields;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->argument('type') == 'index')
            return $this->resolveStubPath('/stubs/index.stub');
        return $this->resolveStubPath('/stubs/form.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
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
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace;
    }

    protected function getPath($name): string
    {
        $name = Str::plural(Str::snake(class_basename($name)));
        
        if ($this->argument('type') == 'index')
            return getcwd().'/resources/views/admin/'.str_replace('\\', '/', $name).'/index.blade.php';

        return getcwd().'/resources/views/admin/'.str_replace('\\', '/', $name).'/form.blade.php';
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
        $stub = parent::replaceClass($stub, $name);
        $pluralModel = Str::plural(class_basename($name));
        $modelVariable = Str::snake(class_basename($name));
        $pluralModelVariable = Str::plural($modelVariable);

        $replaces = collect([
            'DummyPluralModel' => $pluralModel,
            '{{ pluralModel }}' => $pluralModel,
            '{{pluralModel}}' => $pluralModel,
            'DummyModelVariable' => $modelVariable,
            '{{ modelVariable }}' => $modelVariable,
            '{{modelVariable}}' => $modelVariable,
            'DummyPluralModelVariable' => $pluralModelVariable,
            '{{ pluralModelVariable }}' => $pluralModelVariable,
            '{{pluralModelVariable}}' => $pluralModelVariable,
        ]);
        if ($this->argument('type') == 'index')
            $replaces = $replaces->merge($this->getIndexReplaces());
        if ($this->argument('type') == 'form')
            $replaces = $replaces->merge($this->getFormReplaces());

        return str_replace(array_keys($replaces->toArray()), $replaces->toArray(), $stub);
    }

    public function getIndexReplaces(): array
    {
        $search = $this->getFields()->map(fn (InterfaceType $field) => <<<FIELDS
        <div class="form-group col-sm-9 col-md-10">
                            <input type="text" name="{$field->getName()}" class="form-control" placeholder="{$field->getLabel()}" value="{{ request()->{$field->getName()} }}">
                        </div>
        FIELDS)->join("\n\t\t\t\t");
        $columns = $this->getFields()->map(
            fn (InterfaceType $field) => '<th>'.(str_replace('_', ' ', ucfirst($field->getName()))).'</th>'
        )->join("\n\t\t\t\t\t\t");
        $rows = $this->getFields()->map(
            fn (InterfaceType $field) => "<td>{{ \$item->{$field->getName()} }}</td>"
        )->join("\n\t\t\t\t\t\t");

        return [
            '{{search}}' => $search,
            '{{ search }}' => $search,
            'DummySearch' => $search,
            '{{columns}}' => $columns,
            '{{ columns }}' => $columns,
            'DummyColumns' => $columns,
            '{{rows}}' => $rows,
            '{{ rows }}' => $rows,
            'DummyRows' => $rows,
        ];
    }

    public function getFormReplaces(): array
    {
        $fields = $this->getFields()->map(function (InterfaceType $field) {
            return '<div class="form-group col-md-12">'."\n\t\t\t\t\t\t"
            .'<label>'.$field->getLabel().'</label>'."\n\t\t\t\t\t\t"
            .$field->getFormHtml()."\n\t\t\t\t\t"
            .'</div>';
        })->join("\n\t\t\t\t\t");

        return [
            '{{fields}}' => $fields,
            '{{ fields }}' => $fields,
            'DummyFields' => $fields,
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['fields', null, InputOption::VALUE_REQUIRED, 'The name fields separeted with comma'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['type', InputArgument::REQUIRED, 'The type of the class (form|index)'],
        ];
    }

    protected function rootNamespace(): string
    {
        return 'Admin\\';
    }
}
