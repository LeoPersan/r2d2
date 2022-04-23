<?php

namespace Leopersan\R2d2\Types;

class PdfType extends AbstractType
{
    protected string $type = 'pdf';
    protected string $field = 'string';
    protected string $cast = "Pdf::class";
    protected string $use = "App\Casts\Pdf";
    protected bool $arquivo = true;
    protected string $useRequest = "App\Rules\Pdf";
    protected string $rule = "new Pdf";
    protected string $form_html = '<Arquivo accept="application/pdf" v-model="form.data.{$name}" :required="true"></Arquivo>';
}