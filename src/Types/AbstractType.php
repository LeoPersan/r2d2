<?php

namespace Leopersan\R2d2\Types;

abstract class AbstractType implements InterfaceType
{
    protected string $name;
    protected string $type;
    protected string $cast;
    protected string $use;
    protected string $field;
    protected string $rule;
    protected string $useRequest;
    protected bool $arquivo;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCast(): string
    {
        if (!isset($this->cast))
            return '';
        return "'{$this->getName()}' => {$this->cast},";
    }

    public function getUse(): string
    {
        if (!$this->getCast())
            return '';
        return "use {$this->use};";
    }

    public function isArquivo(): bool
    {
        return $this->arquivo ?? false;
    }

    public function getField(): string
    {
        return $this->field ?? $this->getType();
    }

    public function getLabel(): string
    {
        return str_replace('_', ' ', ucfirst($this->getName()));
    }

    public function getPath(): string
    {
        if (!$this->isArquivo())
            return '';
        return "'{$this->getName()}' => '{{ pluralModelVariable }}/{$this->getName()}',";
    }

    public function getRule(): string
    {
        if (!isset($this->rule))
            return '';
        return "'{$this->getName()}' => ['required', {$this->rule}],";
    }

    public function getUseRequest(): string
    {
        if (!isset($this->useRequest))
            return '';
        return "use {$this->useRequest};";
    }
}