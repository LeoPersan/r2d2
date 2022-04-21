<?php

namespace Leopersan\R2d2\Types;

class StringType extends AbstractType
{
    protected string $type = 'string';
    protected string $rule = "'string'";
}