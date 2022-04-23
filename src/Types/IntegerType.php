<?php

namespace Leopersan\R2d2\Types;

class IntegerType extends AbstractType
{
    protected string $type = 'integer';
    protected string $rule = "'numeric'";
    protected string $form_html = '<input type="number" class="form-control" v-model="form.data.{$name}" maxlength="190" required/>';
}