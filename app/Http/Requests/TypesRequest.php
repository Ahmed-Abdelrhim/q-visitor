<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:types,name',
            'status' => 'required|numeric',
            'level' => 'required',
            'role_one' => 'required|numeric',
            'role_two' => 'nullable|numeric'
        ];
    }
}
