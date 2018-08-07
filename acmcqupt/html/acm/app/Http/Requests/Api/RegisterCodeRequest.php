<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class RegisterCodeRequest extends FormRequest
{

    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        //
    }
}
