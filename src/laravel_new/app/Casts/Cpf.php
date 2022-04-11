<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Cpf implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return preg_replace('/([^\d]+)/', '', $value);
    }
}
