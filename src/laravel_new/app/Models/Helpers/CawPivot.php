<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class CawPivot extends CawModel
{
    use AsPivot;

    public $incrementing = false;

    protected $guarded = [];
}
