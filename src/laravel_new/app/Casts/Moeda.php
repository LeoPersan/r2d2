<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Moeda implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return round($value, 2, PHP_ROUND_HALF_DOWN);
    }

    public function set($model, $key, $value, $attributes)
    {
        return round($value, 2, PHP_ROUND_HALF_DOWN);
    }
}
