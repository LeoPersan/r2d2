<?php

namespace Leopersan\R2d2\Types;

class CpfCnpjType extends AbstractType
{
    protected string $type = 'cpf_cnpj';
    protected string $cast = "CpfCnpj::class";
    protected string $use = "App\Casts\CpfCnpj";
    protected string $useRequest = "App\Rules\CpfCnpj";
    protected string $rule = "new CpfCnpj";
}