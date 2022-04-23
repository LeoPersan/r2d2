<?php

namespace Leopersan\R2d2\Types;

class CpfType extends AbstractType
{
    protected string $type = 'cpf';
    protected string $cast = "Cpf::class";
    protected string $use = "App\Casts\Cpf";
    protected string $useRequest = "App\Rules\Cpf";
    protected string $rule = "new Cpf";
    protected string $form_html = '<the-mask type="tel" maks="###.###.###-##" class="form-control" v-model="form.data.{$name}" required></the-mask>';
}