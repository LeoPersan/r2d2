<?php

namespace Leopersan\R2d2\Types;

class CnpjType extends AbstractType
{
    protected string $type = 'cnpj';
    protected string $cast = "Cnpj::class";
    protected string $use = "App\Casts\Cnpj";
    protected string $useRequest = "App\Rules\Cnpj";
    protected string $rule = "new Cnpj";
}