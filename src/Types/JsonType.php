<?php

namespace Leopersan\R2d2\Types;

class JsonType extends AbstractType
{
    protected string $type = 'json';
    protected string $cast = "'json'";
}