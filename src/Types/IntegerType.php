<?php

namespace Leopersan\R2d2\Types;

class IntegerType extends AbstractType
{
    protected string $type = 'integer';
    protected string $rule = "'numeric'";
}