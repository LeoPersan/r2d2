<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CpfCnpj implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (strlen($value) > 11)
            return (new Cnpj)->get($model, $key, $value, $attributes);
        return (new Cpf)->get($model, $key, $value, $attributes);
    }

    public function set($model, $key, $value, $attributes)
    {
        return preg_replace('/([^\d]+)/', '', $value);
    }
}
