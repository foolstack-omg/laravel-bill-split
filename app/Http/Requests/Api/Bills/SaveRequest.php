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
            'title' => 'required|string',
            'description' => 'string|nullable',
            'money' => 'required|numeric|min:0',
            'participants' => 'array',
            'participants.*.user_id' => 'required|int',
            'participants.*.split_money' => 'required|numeric',
            'participants.*.fixed' => 'required|boolean',
            'participants.*.paid' => 'required|boolean',
            'bill_items' => 'array',
            'bill_items.*.id' => 'int|nullable',
            'bill_items.*.title' => 'required|string',
            'bill_items.*.money' => 'required|numeric|min:0',

        ];
    }
}
