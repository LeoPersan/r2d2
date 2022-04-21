<?php

namespace Leopersan\R2d2\Commands\View;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;
use Leopersan\R2d2\Commands\GeneratorCommand;
use Leopersan\R2d2\Commands\Traits\ParseFields;

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
        $search = $this->getOnlyFields()->map(function ($field) {
            $label = str_replace('_', ' ', ucfirst($field));
            return  <<<FIELDS
            <div class="form-group col-sm-9 col-md-10">
                                <input type="text" name="$field" class="form-control" placeholder="$label" value="{{ request()->$field }}">
                            </div>
            FIELDS;
        })->join("\n\t\t\t\t");
        $columns = $this->getOnlyFields()->map(
            fn ($field) => '<th>'.(str_replace('_', ' ', ucfirst($field))).'</th>'
        )->join("\n\t\t\t\t\t\t");
        $rows = $this->getOnlyFields()->map(
            fn ($field) => "<td>{{ \$item->{$field} }}</td>"
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
        $methods = [
            'string' => 'fieldString',
            'integer' => 'fieldInteger',
            'decimal' => 'fieldDecimal',
            'text' => 'fieldText',
            'email' => 'fieldEmail',
            'boolean' => 'fieldCheck',
            'cnpj' => 'fieldCnpj',
            'cpf' => 'fieldCpf',
            'cpf_cnpj' => 'fieldCpfCnpj',
            'telefone' => 'fieldTelefone',
            'pdf' => 'fieldPdf',
            'imagem' => 'fieldImagem',
        ];
        $fields = $this->getFields()->map(function ($field) use ($methods) {
            $label = str_replace('_', ' ', ucfirst($field['name']));
            return '<div class="form-group col-md-12">'."\n\t\t\t\t\t\t"
            .'<label>'.$label.'</label>'."\n\t\t\t\t\t\t"
            .$this->{$methods[$field['type']]}($field['name'])."\n\t\t\t\t\t"
            .'</div>';
        })->join("\n\t\t\t\t\t");

        return [
            '{{fields}}' => $fields,
            '{{ fields }}' => $fields,
            'DummyFields' => $fields,
        ];
    }

    public function fieldString(string $field): string
    {
        return  '<input type="text" class="form-control" v-model="form.data.'.$field.'" maxlength="190" required/>';
    }

    public function fieldEmail(string $field): string
    {
        return  '<input type="email" class="form-control" v-model="form.data.'.$field.'" maxlength="190" required/>';
    }

    public function fieldInteger(string $field): string
    {
        return  '<input type="number" class="form-control" v-model="form.data.'.$field.'" required/>';
    }

    public function fieldDecimal(string $field): string
    {
        return  '<input type="number" step="0.01" class="form-control" v-model="form.data.'.$field.'" required/>';
    }

    public function fieldText(string $field): string
    {
        return  '<tinymce api_key="{{ config(\'services.tiny.key\') }}" v-model="form.data.'.$field.'" url_upload_imagem="{{ route(\'upload_imagens\') }}"></tinymce>';
    }

    public function fieldCheck(string $field): string
    {
        return  '<input type="text" class="form-control" v-model="form.data.'.$field.'" maxlength="190" required/>';
    }

    public function fieldCnpj(string $field): string
    {
        return  '<the-mask type="tel" maks="##.###.###/####-##" class="form-control" v-model="form.data.'.$field.'" required></the-mask>';
    }

    public function fieldCpf(string $field): string
    {
        return  '<the-mask type="tel" maks="###.###.###-##" class="form-control" v-model="form.data.'.$field.'" required></the-mask>';
    }

    public function fieldCpfCnpj(string $field): string
    {
        return  '<the-mask type="tel" :maks="[\'###.###.###-##\', \'##.###.###/####-##\']" class="form-control" v-model="form.data.'.$field.'" required></the-mask>';
    }

    public function fieldTelefone(string $field): string
    {
        return  '<the-mask type="tel" maks="[\'(##) ####-####\', \'(##) #####-####\']" class="form-control" v-model="form.data.'.$field.'" required></the-mask>';
    }

    public function fieldPdf(string $field): string
    {
        return  '<Arquivo accept="application/pdf" v-model="form.data.'.$field.'" :required="true"></Arquivo>';
    }

    public function fieldImagem(string $field): string
    {
        return  '<Imagem v-model="form.data.'.$field.'" :required="true"></Imagem>';
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
