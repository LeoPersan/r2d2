<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class Senha extends FormRequest
{
    public function rules()
    {
        return [
            'current_password' => 'required|password:admin',
            'new_password' => 'required|min:6|confirmed',
        ];
    }
}
