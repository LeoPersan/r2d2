<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class CawLog extends Model
{
    protected $table    =  'log';
    protected $fillable = [
        'tablename',
        'id_table',
        'id_user',
        'dados',
        'data',
        'ip'
    ];

    protected $hidden = [];
    public $timestamps = false;
}
