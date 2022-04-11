<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Arquivos implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return $model->{$key}()->get()->map(function ($item) {
            return $item->url;
        })->toArray();
    }

    public function set($model, $key, $arquivos, $attributes)
    {
        $model->{$key}()->get()->each(function ($arquivo) use ($arquivos) {
            if (!in_array($arquivo->url, $arquivos))
                $arquivo->delete();
        });
        collect($arquivos)->each(function ($arquivo) use ($model, $key) {
            if (preg_match('/^data:/', $arquivo))
                $model->{$key}()->create(['imagem' => $arquivo]);
        });
        return true;
    }
}
