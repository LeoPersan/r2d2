<?php

namespace Leopersan\R2d2\Types;

class DecimalType extends AbstractType
{
    protected string $type = 'decimal';
    protected string $rule = "'numeric'";
}