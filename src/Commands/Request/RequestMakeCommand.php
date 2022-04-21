<?php

namespace Leopersan\R2d2\Commands\Request;

use Illuminate\Support\Str;
use Leopersan\R2d2\Commands\GeneratorCommand;
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

    protected $uses = [];

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
        $rules = $types->filter(fn ($type, $index) => method_exists($this, 'getRule'.Str::studly($type)))
                        ->map(fn ($type, $index) => $this->{'getRule'.Str::studly($type)}($fields[$index]))
                        ->join("\n\t\t\t");

        return str_replace([
            '{{ rules }}', '{{ uses }}'
        ],[
            $rules, implode("\n", $this->uses)
        ],parent::replaceClass($stub, $name));
    }

    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_REQUIRED, 'The name fields separeted with comma'],
        ];
    }

    public function getRuleCnpj($field)
    {
        $this->uses[] = 'use App\Rules\Cnpj;';
        return "'{$field}' => ['required', new Cnpj],";
    }

    public function getRuleCpf($field)
    {
        $this->uses[] = 'use App\Rules\Cpf;';
        return "'{$field}' => ['required', new Cpf],";
    }

    public function getRuleCpfCnpj($field)
    {
        $this->uses[] = 'use App\Rules\CpfCnpj;';
        return "'{$field}' => ['required', new CpfCnpj],";
    }

    public function getRulePdf($field)
    {
        $this->uses[] = 'use App\Rules\Pdf;';
        return "'{$field}' => ['required', new Pdf],";
    }

    public function getRuleImagem($field)
    {
        $this->uses[] = 'use App\Rules\Imagem;';
        return "'{$field}' => ['required', new Imagem],";
    }

    public function getRuleTelefone($field)
    {
        $this->uses[] = 'use App\Rules\Telefone;';
        return "'{$field}' => ['required', new Telefone],";
    }

    public function getRuleEmail($field)
    {
        return "'{$field}' => 'required|email:rfc,dns',";
    }

    public function getRuleBoolean($field)
    {
        return "'{$field}' => 'required|boolean',";
    }

    public function getRuleString($field)
    {
        return "'{$field}' => 'required|string',";
    }

    public function getRuleInteger($field)
    {
        return "'{$field}' => 'required|numeric',";
    }

    public function getRuleDecimal($field)
    {
        return "'{$field}' => 'required|numeric',";
    }

    public function getRuleFk($field)
    {
        $table = Str::plural(preg_replace('/(.*)_id$/', '$1', $field));
        $key = preg_replace('/.*_(id)$/', '$1', $field);
        return "'{$field}' => 'required|exists:{$table},{$key}',";
    }
}
