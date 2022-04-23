<?php

namespace Leopersan\R2d2\Types;

class FkType extends AbstractType
{
    protected string $type = 'fk';

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
}