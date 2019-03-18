<?php

namespace App\Http\Requests\Api\Activities;


use App\Http\Requests\Api\FormRequest;

class SaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'int',
            'name' => 'required|string|between:1,10',
        ];
    }
}
