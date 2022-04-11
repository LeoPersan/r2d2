<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Telefone implements Rule
{
    public function passes($attribute, $value)
    {
        if (preg_match('/\(?\d{2}\)?\s*\d{4,5}-?\d{4}/', $value))
            return true;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute é um telefone inválido.';
    }
}
