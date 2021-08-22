<?php

namespace Modules\Mess\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodMenuItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'itemName' => 'required',
            'uomId' => 'required',
            'value' => 'required',
            'cost' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
