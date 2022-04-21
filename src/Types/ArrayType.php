<?php

namespace Leopersan\R2d2\Types;

class ArrayType extends AbstractType
{
    protected string $type = 'array';
    protected string $cast = "'array'";
}