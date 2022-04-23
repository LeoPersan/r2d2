<?php

namespace Leopersan\R2d2\Types;

class ImagemType extends AbstractType
{
    protected string $type = 'imagem';
    protected string $field = 'string';
    protected string $cast = "Imagem::class";
    protected string $use = "App\Casts\Imagem";
    protected bool $arquivo = true;
    protected string $useRequest = "App\Rules\Imagem";
    protected string $rule = "new Imagem";
    protected string $form_html = '<Imagem v-model="form.data.{$name}" :required="true"></Imagem>';
}