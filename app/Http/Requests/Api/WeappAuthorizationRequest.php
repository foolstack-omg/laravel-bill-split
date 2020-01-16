<?php

namespace App\Http\Requests\Api;

class WeappAuthorizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
            'name' => 'required|string',
            'avatar_url' => 'present|string|nullable',
            'gender' => 'present|int',
            'city' => 'present|string|nullable',
            'province' => 'present|string|nullable',
            'country' => 'present|string|nullable'
        ];
    }
}
