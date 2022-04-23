<?php

namespace Leopersan\R2d2\Types;

class EmailType extends AbstractType
{
    protected string $type = 'email';
    protected string $field = 'string';
    protected string $rule = "'email:rfc,dns'";
    protected string $form_html = '<input type="email" class="form-control" v-model="form.data.{$name}" maxlength="190" required/>';
}