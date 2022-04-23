<?php

namespace Leopersan\R2d2\Types;

class CnpjType extends AbstractType
{
    protected string $type = 'cnpj';
    protected string $cast = "Cnpj::class";
    protected string $use = "App\Casts\Cnpj";
    protected string $useRequest = "App\Rules\Cnpj";
    protected string $rule = "new Cnpj";
    protected string $form_html = '<the-mask type="tel" maks="##.###.###/####-##" class="form-control" v-model="form.data.{$name}" required></the-mask>';
}