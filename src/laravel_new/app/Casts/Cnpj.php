<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Cnpj implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return preg_replace('/([^\d]+)/', '', $value);
    }
}
