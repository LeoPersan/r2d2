<?php

namespace Leopersan\R2d2\Types;

class CpfType extends AbstractType
{
    protected string $type = 'cpf';
    protected string $cast = "Cpf::class";
    protected string $use = "App\Casts\Cpf";
    protected string $useRequest = "App\Rules\Cpf";
    protected string $rule = "new Cpf";
}