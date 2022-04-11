<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class Pdf implements Rule
{
    public function passes($attribute, $value)
    {
        if ($value instanceof UploadedFile)
            return preg_match('/application\/pdf/', $value->getMimeType());
        elseif (\preg_match('/data:application\/pdf;base64/', $value))
            return true;
        elseif (strpos($value, asset('storage')) !== false)
            return true;
        return false;
    }

    public function message()
    {
        return 'O campo :attribute deve ser do tipo PDF.';
    }
}
