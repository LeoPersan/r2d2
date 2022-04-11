<?php

namespace App\Models;

use App\Models\Helpers\CawModelUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends CawModelUser
{
    use HasApiTokens, HasFactory;

    const PERMISSOES = ['Admin'];
    protected $fillable = ['nome', 'email', 'senha', 'permissoes', 'ativo'];
    protected $hidden = ['senha'];
    protected $casts = [
        'ativo' => 'boolean',
        'permissoes' => 'array',
    ];

    public function setSenhaAttribute($value)
    {
        return $this->attributes['senha'] = Hash::make($value);
    }

    public function getPermissoesAttribute()
    {
        return $this->attributes['permissoes'] ? json_decode($this->attributes['permissoes'], true) : [];
    }

    public function scopePesquisar(Builder $builder, Request $request)
    {
        return $builder->nomeEmail($request->nome_email)
                        ->permissao($request->permissao);
    }

    public function scopeNomeEmail(Builder $builder, $nome_email = false)
    {
        if (!$nome_email)
            return $builder;
        return $builder->where(function (Builder $builder) use ($nome_email) {
            return $builder->where('nome', 'like', '%'.$nome_email.'%')->orWhere('email', 'like', '%'.$nome_email.'%');
        });
    }

    public function scopePermissao(Builder $builder, $permissao = false)
    {
        if (!$permissao)
            return $builder;
        return $builder->whereJsonContains('permissoes', $permissao);
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('ordem', function (Builder $builder) {
            return $builder->orderBy('nome');
        });
    }
}
