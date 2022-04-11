<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Telefone implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return $value ? preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value) : $value;
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value ? preg_replace('/([^\d]+)/', '', $value) : $value;
    }
}
