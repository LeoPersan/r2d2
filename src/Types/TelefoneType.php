<?php

namespace Leopersan\R2d2\Types;

class TelefoneType extends AbstractType
{
    protected string $type = 'telefone';
    protected string $cast = "Telefone::class";
    protected string $use = "App\Casts\Telefone";
    protected string $useRequest = "App\Rules\Telefone";
    protected string $rule = "new Telefone";
}