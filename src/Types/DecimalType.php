<?php

namespace Leopersan\R2d2\Types;

class DecimalType extends AbstractType
{
    protected string $type = 'decimal';
    protected string $rule = "'numeric'";
    protected string $form_html = '<input type="number" step="0.01" class="form-control" v-model="form.data.{$name}" maxlength="190" required/>';
}