<?php

namespace App\Http\Requests\Api\Bills;

use App\Http\Requests\Api\FormRequest;
use App\Http\Requests\Request;

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
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'money' => 'required|numeric|min:0',
            'participants.data' => 'array',
            'participants.data.*.user_id' => 'required|int',
            'participants.data.*.split_money' => 'required|numeric',
            'participants.data.*.fixed' => 'required|boolean',
            'participants.data.*.paid' => 'required|boolean',
            'items.data' => 'array',
            'items.data.*.id' => 'int|nullable',
            'items.data.*.title' => 'required|string',
            'items.data.*.money' => 'required|numeric|min:0',

        ];
    }
}
