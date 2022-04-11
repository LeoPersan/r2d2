<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cep implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/\d{5}-?\d{3}/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute é um CEP inválido.';
    }
}
