<?php

namespace Leopersan\R2d2\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Leopersan\R2d2\Types\InterfaceType;
use Leopersan\R2d2\Types\StringType;

trait ParseFields
{
    protected ?Collection $fields = null;

    protected function parseFields()
    {
        $this->fields = collect(explode(',', $this->option('fields')))->mapWithkeys(
            function (string $field) {
                $field = explode(':', $field);
                $name = trim($field[0]);
                $type = 'Leopersan\R2d2\Types\\' . Str::studly(trim($field[1] ?? 'string')) . 'Type';
                if (class_exists($type))
                    return [$name => new $type($name)];
                return [$name => new StringType($name)];
            }
        );
    }

    protected function getFields(): Collection
    {
        if ($this->fields === null)
            $this->parseFields();
        return $this->fields;
    }

    protected function getOnlyFields(): Collection
    {
        if ($this->fields === null)
            $this->parseFields();
        return $this->fields->map(fn (InterfaceType $field) => $field->getName());
    }

    protected function getOnlyTypes(): Collection
    {
        if ($this->fields === null)
            $this->parseFields();
        return $this->fields->map(fn (InterfaceType $field) => $field->getType());
    }

    protected function getFieldsString(): string
    {
        return $this->option('fields');
    }
}