<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Boolean implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return (bool) $value;
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value ? 1 : 0;
    }
}
