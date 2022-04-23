<?php

namespace Leopersan\R2d2\Types;

class TextType extends AbstractType
{
    protected string $type = 'text';
    protected string $rule = "'string'";
    protected string $form_html = '<tinymce api_key="{{ config(\'services.tiny.key\') }}" v-model="form.data.{$name}" url_upload_imagem="{{ route(\'upload_imagens\') }}"></tinymce>';
}