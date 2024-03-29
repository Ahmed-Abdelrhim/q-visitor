<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
        if ($this->employee) {
            $email    = ['required', 'email', 'string', Rule::unique("users", "email")->ignore($this->employee->user->id)];
        } else {
            $email    = ['required', 'email', 'string', 'unique:users,email'];
        }
        return [
            'first_name'                => 'required|string|max:20',
            'last_name'                 => 'required|string|max:20',
            'nickname'                  => 'nullable|string|max:20',
            'phone'                     => 'required|string|max:20',
            'email'                     => $email,
            'department_id'             => 'required|numeric',
            'designation_id'            => 'required|numeric',
            'gender'                    => 'required|numeric',
            'emp_one'                   => 'nullable|numeric|exists:employees,id',
            'emp_two'                   => 'nullable|numeric|exists:employees,id',
            'date_of_joining'           => 'required',
            'about'                     => 'nullable|max:255',
            'role'                      => 'nullable|numeric',
            'image'                     => 'image|mimes:jpeg,png,jpg|max:5098',
        ];
    }
}
