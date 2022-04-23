<?php

namespace Leopersan\R2d2\Types;

class TextType extends AbstractType
{
    protected string $type = 'text';
    protected string $rule = "'string'";
}