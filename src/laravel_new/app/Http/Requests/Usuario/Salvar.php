<?php

namespace App\Http\Requests\Usuario;

use App\Models\Usuario;
use App\Rules\Telefone;
use Illuminate\Foundation\Http\FormRequest;

class Salvar extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|unique:usuarios,email'.($this->id ? ','.$this->id : ''),
            'nome' => 'required|min:3|unique:usuarios,nome'.($this->id ? ','.$this->id : ''),
            'celular' => ['nullable', new Telefone],
            'ativo' => 'required|boolean',
            'permissoes' => 'nullable|array',
            'permissoes.*' => 'in:"'.implode('","', Usuario::PERMISSOES).'"',
        ];
    }
}
