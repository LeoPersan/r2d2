<?php

namespace Leopersan\R2d2\Commands\Traits;

use Illuminate\Support\Collection;

trait ParseFields
{
    protected ?Collection $fields = null;

    protected function parseFields()
    {
        $this->fields = collect(explode(',', $this->option('fields')))->map(
            function ($field) {
                $field = explode(':', $field);
                $name = trim($field[0]);
                $type = trim($field[1] ?? 'string');
                $type = $this->types[$type] ?? $type;
                return [
                    'name' => $name,
                    'type' => $type,
                ];
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
        return $this->fields->map(function ($field) {
            return $field['name'];
        });
    }

    protected function getOnlyTypes(): Collection
    {
        if ($this->fields === null)
            $this->parseFields();
        return $this->fields->map(function ($field) {
            return $field['type'];
        });
    }

    protected function getFieldsString(): string
    {
        return $this->option('fields');
    }

}