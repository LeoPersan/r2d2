<?php

namespace Leopersan\R2d2\Types;

class BooleanType extends AbstractType
{
    protected string $type = 'boolean';
    protected string $cast = "'boolean'";
    protected string $rule = "'boolean'";
}