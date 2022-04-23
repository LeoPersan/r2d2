<?php

namespace Leopersan\R2d2\Types;

class FkType extends AbstractType
{
    protected string $type = 'fk';

    public function getFormHtml(): string
    {
        $variabel = explode('_', $this->getName())[0];
        return "<select v-model=\"form.data.{$this->getName()}\" class=\"form-control\" required>"
        ."\n\t\t\t\t\t\t\t@foreach(\${$variabel}s as \${$variabel})"
        ."\n\t\t\t\t\t\t\t\t<option value=\"{{ \${$variabel}->id }}\">{{ \${$variabel}->nome }}</option>"
        ."\n\t\t\t\t\t\t\t@endforeach"
        ."\n\t\t\t\t\t\t</select>";
    }

    public function getLabel(): string
    {
        return ucfirst(explode('_', $this->getName())[0]);
    }

    public function getMigrationUpField(string $type = 'create'): string
    {
        return $type == 'create'
                ? "\$table->foreignId('{$this->getName()}')->constrained()->onDelete('restrict')->onUpdate('restrict');"
                : "\$table->foreignId('{$this->getName()}')->nullable()->constrained()->onDelete('restrict')->onUpdate('restrict');";
    }

    public function getMigrationDownField(string $type = 'create'): string
    {
        return $type == 'create'
                ? ''
                : "\$table->dropForeignId(['{$this->getName()}']);";
    }

    public function getBelongsTo(): string
    {
        return "public function ".(explode('_', $this->getName())[0])."()\n\t{\n\t\treturn \$this->belongsTo({$this->getLabel()}::class);\n\t}";
    }
}