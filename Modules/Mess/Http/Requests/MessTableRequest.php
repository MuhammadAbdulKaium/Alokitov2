<?php

namespace Modules\Mess\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessTableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tableName' => 'required',
            'totalSeats' => 'required|numeric|min:4',
            'totalHighSeats' => 'required|numeric|min:2',
            'employeeId' => 'required'
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
